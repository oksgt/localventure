<?php

namespace App\Http\Controllers\landingpage;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Mail\BookingEmail;
use App\Models\BankAccount;
use App\Models\Destination;
use App\Models\DestinationGallery;
use App\Models\PaymentConfirmation;
use App\Models\PaymentType;
use App\Models\Pricing;
use App\Models\TicketOrder;
use App\Models\TicketOrderDetail;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Str;

class BookingController extends Controller
{

    public function searchTickets(Request $request)
    {
        $request->validate([
            'destination_id' => 'required|integer',
            'daterange' => 'required|string',
            'people_count' => 'required|integer'
        ]);

        session()->flash('search_data', $request->all());

        return response()->json([
            'redirect' => route('tickets.results') // ✅ Sends redirect URL in JSON response
        ]);
    }

    public function showResults()
    {
        // Retrieve search data from session
        $searchData = session('search_data');
        $destinations = Destination::with('images')->orderBy('id', 'asc')->get(); // ✅ Order by ID ascending
        $destinationNames = $destinations->pluck('name')->toArray();

        if (!$searchData) {
            return redirect()->route('landing-page.home'); // ✅ Redirect to home if no search data
        }

        $selectedDestination = $destinations->firstWhere('id', $searchData['destination_id']);
        $selectedDestinationName = $selectedDestination ? $selectedDestination->name : 'Unknown Destination';

        $selectedImage = $selectedDestination && $selectedDestination->images->isNotEmpty()
            ? asset('storage/destination/' . basename($selectedDestination->images->first()->image_url))
            : asset('storage/destination/bg-booking-header.png');

        $provinces = DB::table('reg_provinces')
            ->orderBy('name', 'asc')
            ->get();

        $destinationId = $searchData['destination_id'] ?? null; // ✅ Get selected destination ID

        //get guest_types data
        $guestTypes = DB::table('guest_types')
            ->whereNull('deleted_at') // ✅ Ensure it's not deleted
            ->select('id', 'name')
            ->orderBy('name', 'asc')
            ->get();

        $pricing = DB::table('pricing')
            ->join('guest_types as gt', 'gt.id', '=', 'pricing.guest_type_id')
            ->select(
                'pricing.id',
                'pricing.destination_id',
                'pricing.guest_type_id',
                'gt.name as guest_name',
                'gt.id as guest_name_id',
                'pricing.day_type',
                'pricing.base_price',
                'pricing.insurance_price',
                'pricing.final_price'
            )
            ->whereNull('pricing.deleted_at') // ✅ Exclude soft-deleted records
            ->where('pricing.destination_id', $destinationId) // ✅ Filter by selected destination ID
            ->orderBy('pricing.day_type', 'asc')
            ->orderBy('guest_name', 'asc')
            ->get();

        $searchData['pricing_available'] = true;

        if ($pricing->isEmpty()) {
            $searchData['pricing_available'] = false; // ✅ No pricing data found
        }

        $date = Carbon::createFromFormat('m/d/Y', $searchData['daterange']); // ✅ Correct parsing

        if ($date->isWeekend()) {
            $searchData['day_type'] = 'Weekend';
        } else {
            $searchData['day_type'] = 'Weekday';
        }

        $jenisHari = $searchData['day_type'];

        $paymentTypes = PaymentType::where('status', 1)
            ->whereNull('deleted_at') // ✅ Ensure it's not deleted
            ->whereNot('payment_type_name', 'Cash')
            ->get(['id', 'payment_type_name as name']);

        $paymentTypesWithImage = PaymentType::where('status', 1)
            ->whereNull('deleted_at') // ✅ Ensure it's not deleted
            ->whereNotNull('payment_image') // ✅ Ensure image URL is not null
            ->get(['id', 'payment_image'])
            ->first();

        // dd($jenisHari); // ✅ Debugging line to check search data

        return view(
            'landing-page.booking',
            compact('jenisHari', 'guestTypes', 'searchData', 'destinations', 'destinationNames', 'selectedDestinationName', 'selectedImage', 'provinces', 'pricing', 'paymentTypes', 'paymentTypesWithImage')
        ); // ✅ Pass data to results page
    }

    public function getProvinces(Request $request)
    {
        $provinces = DB::table('reg_provinces')
            ->where('name', 'LIKE', "%{$request->search}%")
            ->orderBy('name', 'asc')
            ->get();

        return response()->json($provinces);
    }

    public function getRegencies(Request $request)
    {
        $regencies = DB::table('reg_regencies')
            ->where('province_id', $request->province_id)
            ->where('name', 'LIKE', "%{$request->search}%")
            ->orderBy('name', 'asc')
            ->get();

        return response()->json($regencies);
    }

    public function getDistricts(Request $request)
    {
        $districts = DB::table('reg_districts')
            ->where('regency_id', $request->regency_id)
            ->where('name', 'LIKE', "%{$request->search}%")
            ->orderBy('name', 'asc')
            ->get();

        return response()->json($districts);
    }

    public function getPricing(Request $request)
    {
        $pricing = Pricing::where('destination_id', $request->destination_id)
            ->join('guest_types as gt', 'gt.id', '=', 'pricing.guest_type_id')
            ->select('pricing.*', 'gt.name as guest_name')
            ->whereNull('pricing.deleted_at')
            ->orderBy('pricing.day_type', 'asc')
            ->orderBy('gt.name', 'asc')
            ->get();

        return response()->json($pricing);
    }

    public function updateDayType(Request $request)
    {
        // Validate the request
        $request->validate([
            'day_type' => 'required|string|in:Weekday,Weekend',
        ]);

        // Retrieve the day type from the request
        $dayType = $request->input('day_type');

        // Store it in session or process it further
        Session::put('selected_day_type', $dayType);

        return response()->json([
            'success' => true,
            'message' => "Day type updated successfully!",
            'day_type' => $dayType
        ]);
    }

    public function getFinishPayment(Request $request)
    {
        $simulatedPayloads = [
        [
            "formData" => [
                "selectDestinationId" => 10,
                "date" => "04 - June - 2025",
                "people_count" => 4,
                "name" => "Yusuf Ramadhan",
                "address" => "Jl. Cendrawasih No. 88, Banyumas",
                "provinceSearch" => "35",
                "regencySearch" => "3522",
                "districtSearch" => "352215",
                "phone" => "081234567811",
                "origin" => "lorem ipsum",
                "email" => "yusuf.ramadhan@mail.com",
                "anak-anak" => 1,
                "dewasa" => 2,
                "mancanegara" => 1,
                "selectPaymentId" => 1,
                "total_price" => 185000,
                "bankSelection" => 1
            ]
        ],
        [
            "formData" => [
                "selectDestinationId" => 11,
                "date" => "05 - June - 2025",
                "people_count" => 6,
                "name" => "Dina Mariani",
                "address" => "Perum Wijaya Kusuma, Sokaraja",
                "provinceSearch" => "35",
                "regencySearch" => "3522",
                "districtSearch" => "352215",
                "phone" => "082344556677",
                "origin" => "sed ut perspiciatis",
                "email" => "dina.mariani@mail.com",
                "anak-anak" => 2,
                "dewasa" => 3,
                "mancanegara" => 1,
                "selectPaymentId" => 3,
                "total_price" => 290000,
                "bankSelection" => 1
            ]
        ],
        [
            "formData" => [
                "selectDestinationId" => 10,
                "date" => "07 - June - 2025",
                "people_count" => 2,
                "name" => "Galih Nugroho",
                "address" => "Jl. Manggis No. 6, Purwokerto Barat",
                "provinceSearch" => "35",
                "regencySearch" => "3522",
                "districtSearch" => "352215",
                "phone" => "085566778899",
                "origin" => "voluptatem accusantium",
                "email" => "galih.n@mail.com",
                "anak-anak" => 1,
                "dewasa" => 1,
                "mancanegara" => 0,
                "selectPaymentId" => 1,
                "total_price" => 90000,
                "bankSelection" => 1
            ]
        ],
        [
            "formData" => [
                "selectDestinationId" => 11,
                "date" => "10 - June - 2025",
                "people_count" => 8,
                "name" => "Maya Kartika",
                "address" => "Jl. Sawo Kembar No. 15, Karanglewas",
                "provinceSearch" => "35",
                "regencySearch" => "3522",
                "districtSearch" => "352215",
                "phone" => "089911223344",
                "origin" => "doloremque laudantium",
                "email" => "maya.kartika@mail.com",
                "anak-anak" => 2,
                "dewasa" => 5,
                "mancanegara" => 1,
                "selectPaymentId" => 3,
                "total_price" => 360000,
                "bankSelection" => 1
            ]
        ],
        [
            "formData" => [
                "selectDestinationId" => 10,
                "date" => "12 - June - 2025",
                "people_count" => 3,
                "name" => "Rahmat Fajar",
                "address" => "Jl. Anggrek Raya No. 10, Kedungwuluh",
                "provinceSearch" => "35",
                "regencySearch" => "3522",
                "districtSearch" => "352215",
                "phone" => "087766554433",
                "origin" => "totam rem aperiam",
                "email" => "rahmat.fajar@mail.com",
                "anak-anak" => 0,
                "dewasa" => 2,
                "mancanegara" => 1,
                "selectPaymentId" => 3,
                "total_price" => 150000,
                "bankSelection" => 1
            ]
        ],
        [
            "formData" => [
                "selectDestinationId" => 11,
                "date" => "14 - June - 2025",
                "people_count" => 5,
                "name" => "Anisa Prameswari",
                "address" => "Villa Kusuma Indah Blok C3, Banyumas",
                "provinceSearch" => "35",
                "regencySearch" => "3522",
                "districtSearch" => "352215",
                "phone" => "081776655443",
                "origin" => "eaque ipsa quae",
                "email" => "anisa.pw@mail.com",
                "anak-anak" => 1,
                "dewasa" => 3,
                "mancanegara" => 1,
                "selectPaymentId" => 1,
                "total_price" => 220000,
                "bankSelection" => 1
            ]
        ],
        [
            "formData" => [
                "selectDestinationId" => 10,
                "date" => "16 - June - 2025",
                "people_count" => 7,
                "name" => "Rizka Handayani",
                "address" => "Jl. Durian No. 3, Kembaran",
                "provinceSearch" => "35",
                "regencySearch" => "3522",
                "districtSearch" => "352215",
                "phone" => "082198877665",
                "origin" => "veritatis et quasi",
                "email" => "rizka.handayani@mail.com",
                "anak-anak" => 2,
                "dewasa" => 4,
                "mancanegara" => 1,
                "selectPaymentId" => 3,
                "total_price" => 310000,
                "bankSelection" => 1
            ]
        ],
        [
            "formData" => [
                "selectDestinationId" => 11,
                "date" => "19 - June - 2025",
                "people_count" => 6,
                "name" => "Fajar Prakoso",
                "address" => "Jl. Apel Manis No. 24, Purwokerto Selatan",
                "provinceSearch" => "35",
                "regencySearch" => "3522",
                "districtSearch" => "352215",
                "phone" => "089922556633",
                "origin" => "architecto beatae vitae",
                "email" => "fajar.p@mail.com",
                "anak-anak" => 1,
                "dewasa" => 4,
                "mancanegara" => 1,
            "selectPaymentId" => 1,
            "total_price" => 265000,
            "bankSelection" => 1
        ]
    ],
    [
        "formData" => [
            "selectDestinationId" => 10,
            "date" => "21 - June - 2025",
            "people_count" => 2,
            "name" => "Wulan Anggraeni",
            "address" => "Jl. Jambu No. 19, Patikraja",
            "provinceSearch" => "35",
            "regencySearch" => "3522",
            "districtSearch" => "352215",
            "phone" => "082187654321",
            "origin" => "nemo enim ipsam",
            "email" => "wulan.ang@mail.com",
            "anak-anak" => 0,
            "dewasa" => 2,
            "mancanegara" => 0,
            "selectPaymentId" => 3,
            "total_price" => 90000,
            "bankSelection" => 1
        ]
    ]
    ];

        $results = [];

        foreach ($simulatedPayloads as $index => $payload) {
            // Simulate a request object
            $simulatedRequest = new Request($payload);

            // Call your original POST-like logic
            $response = $this->finishPayment($simulatedRequest);

            // Collect the results (optional)
            $results[] = [
                'index' => $index + 1,
                'status' => $response->getStatusCode(),
                'data' => $response->getData(),
            ];
        }

        return response()->json([
            'message' => 'Simulation batch executed!',
            'results' => $results,
        ]);

    }

    public function finishPayment(Request $request)
    {
        $validatedData = $request->validate([
            'formData.selectDestinationId' => 'required|exists:destinations,id',
            'formData.date' => 'required',
            'formData.people_count' => 'required|integer|min:1',
            'formData.name' => 'required|string|max:255',
            'formData.address' => 'required|string|max:255',
            'formData.provinceSearch' => 'required',
            'formData.regencySearch' => 'required',
            'formData.districtSearch' => 'required',
            'formData.phone' => 'required|string|max:20',
            'formData.origin' => 'nullable|string',
            'formData.email' => 'nullable|email|max:255',
            'formData.anak-anak' => 'nullable|integer|min:0',
            'formData.dewasa' => 'nullable|integer|min:0',
            'formData.mancanegara' => 'nullable|integer|min:0',
            'formData.selectPaymentId' => 'required',
            'formData.total_price' => 'required|numeric|min:0',
        ]);

        try {
            DB::beginTransaction(); // ✅ Ensures atomicity

            $formattedDate = Carbon::createFromFormat("d - F - Y", $validatedData['formData']['date'])->format("Y-m-d");

            $provinceId = DB::table('reg_provinces')->where('name', $validatedData['formData']['provinceSearch'])->value('id');
            $regencyId = DB::table('reg_regencies')->where('name', $validatedData['formData']['regencySearch'])->value('id');
            $districtId = DB::table('reg_districts')->where('name', $validatedData['formData']['districtSearch'])->value('id');

            $bank_id = null;
            $bankAccount = null;

            if ($validatedData['formData']['selectPaymentId'] == 3) {
                $bankAccount = BankAccount::where('id', $request['formData']['bankSelection'])->first();
                $bank_id = $bankAccount->id;
            }

            $object = [
                'destination_id' => $validatedData['formData']['selectDestinationId'],
                'visitor_type' => ($validatedData['formData']['people_count'] > 1) ? 'group' : 'individual',
                'visit_date' => $formattedDate,
                'visitor_name' => $validatedData['formData']['name'],
                'visitor_address' => $validatedData['formData']['address'],
                'visitor_phone' => $validatedData['formData']['phone'],
                'visitor_origin_description' => $validatedData['formData']['origin'] ?? null,
                'visitor_email' => $validatedData['formData']['email'] ?? null,
                'total_visitor' => $validatedData['formData']['anak-anak'] + $validatedData['formData']['dewasa'] + $validatedData['formData']['mancanegara'],
                'total_price' => 0,
                'billing_number' => $this->generateInvoiceNumber($formattedDate),
                'payment_status' => 'pending',
                'purchasing_type' => 'online',
                'notes' => null,
                'created_by' => auth()->id(),
                'id_kecamatan' => $districtId,
                'id_kabupaten' => $regencyId,
                'id_provinsi' => $provinceId,
                'visitor_male_count' => 0,
                'visitor_female_count' => 0,
                'payment_type_id' => $validatedData['formData']['selectPaymentId'],
                'bank_id' => $bank_id,
            ];

            // ✅ Create Ticket Order
            $ticketOrder = TicketOrder::create($object);

            // ✅ Define Guest Types
            $guestTypes = ['anak-anak', 'dewasa', 'mancanegara'];

            $totalPriceTicketOrder = 0;

            foreach ($guestTypes as $guestTypeName) {
                $qty = $validatedData['formData'][$guestTypeName] ?? 0;

                if ($qty > 0) {
                    // ✅ Retrieve Guest Type ID
                    $guestTypeId = DB::table('guest_types')->where('name', $guestTypeName)->value('id');

                    // ✅ Determine day type
                    $dayType = (Carbon::parse($formattedDate)->isWeekend()) ? 'weekend' : 'weekday';

                    // ✅ Retrieve pricing
                    $pricing = DB::table('pricing')
                        ->where('destination_id', $validatedData['formData']['selectDestinationId'])
                        ->where('guest_type_id', $guestTypeId)
                        ->where('day_type', $dayType)
                        ->first();

                    if ($pricing) {
                        $insurancePrice = $pricing->insurance_price ?? 0;
                        $basePrice = $pricing->base_price;
                        $totalPrice = $insurancePrice + $basePrice;

                        // ✅ Store each guest type individually
                        for ($i = 0; $i < $qty; $i++) {
                            TicketOrderDetail::create([
                                'ticket_code' => $this->generateTicketCode(), // ✅ Generate unique ticket code
                                'order_id' => $ticketOrder->id,
                                'guest_type_id' => $guestTypeId,
                                'day_type' => $dayType,
                                'visit_date' => $formattedDate,
                                'insurance_price' => $insurancePrice,
                                'base_price' => $basePrice,
                                'total_price' => $totalPrice,
                                'qty' => 1, // ✅ Always store individual tickets per row
                                'created_by' => auth()->id(),
                            ]);

                            $totalPriceTicketOrder += $totalPrice;
                        }
                    }
                }
            }

            DB::table('ticket_orders')->where('id', $ticketOrder->id)->update([
                'total_price' => $totalPriceTicketOrder,
            ]);

            DB::commit(); // ✅ Confirm transaction

            $destination = Destination::with('images')->orderBy('id', 'asc')->first();
            $selectedImage = $destination && $destination->images->isNotEmpty()
                ? asset('storage/destination/' . basename($destination->images->first()->image_url))
                : asset('storage/destination/bg-booking-header.png');

            $qris = null;

            if ($validatedData['formData']['selectPaymentId'] == 1) {
                $qris = DB::table('payment_type')
                    ->select('id', 'payment_type_name', 'payment_image')
                    ->where('id', 1)
                    ->whereNull('deleted_at') // ✅ Ensures it's not soft-deleted
                    ->first(); // ✅ Retrieves a single record
            }

            $result = [
                'id' => $ticketOrder->id,
                'invoice_number' => $object['billing_number'],
                'total_price' => $object['total_price'],
                'total_visitor' => $object['total_visitor'],
                'notes' => $object['notes'],
                'payment_status' => $object['payment_status'],
                'payment_type_id' => $object['payment_type_id'],
                'qris' => $qris,
                'bank' => $bankAccount,
            ];

            // dd($result);

            return response()->json([
                'message' => 'Booking created successfully!',
                'encrypted_id' => Crypt::encryptString($ticketOrder->id),
                'data' => $result,
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack(); // ❌ Rollback on error
            return response()->json([
                'message' => 'Error processing booking!',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function showFinishPayment($id)
    {
        $id = Crypt::decryptString($id);
        $ticketOrder = TicketOrder::findOrFail($id);

        if (!$ticketOrder) {
            return 404; // ❌ Not Found
        }

        $qris = null;

        if ($ticketOrder->payment_type_id == 1) {
            $qris = DB::table('payment_type')
                ->select('id', 'payment_type_name', 'payment_image')
                ->where('id', 1)
                ->whereNull('deleted_at') // ✅ Ensures it's not soft-deleted
                ->first(); // ✅ Retrieves a single record
        }

        $bank_id = null;
        $bankAccount = null;

        if ($ticketOrder->payment_type_id == 3) {
            $bankAccount = BankAccount::where('id', $ticketOrder->bank_id)->first();
            $bank_id = $bankAccount->id;
        }

        $result = [
            'id' => $ticketOrder->id,
            'invoice_number' => $ticketOrder->billing_number,
            'total_price' => $ticketOrder->total_price,
            'total_visitor' => $ticketOrder->total_visitor,
            'notes' => $ticketOrder->visitor_origin_description,
            'payment_status' => $ticketOrder->payment_status,
            'payment_type_id' => $ticketOrder->payment_type_id,
            'qris' => $qris,
            'bank' => $bankAccount,
        ];

        $destination = Destination::with('images')->orderBy('id', 'asc')->first();
        $selectedImage = $destination && $destination->images->isNotEmpty()
            ? asset('storage/destination/' . basename($destination->images->first()->image_url))
            : asset('storage/destination/bg-booking-header.png');

        $email_check = DB::table('order_email')->where('ticket_order_id', $ticketOrder->id)
        ->where('email_type', 'invoice_unpaid')
        ->first();

        $encrypted_id = Crypt::encrypt($ticketOrder->id);

        if (!$email_check) {
            $insert = DB::table('order_email')->insert([
                'ticket_order_id' => $ticketOrder->id,
                'email_type' => 'invoice_unpaid',
                'sent_at' => now(),
            ]);
            Mail::to($ticketOrder->visitor_email)->send(new BookingEmail($destination, $selectedImage, $result, $encrypted_id));
        }

        return view('landing-page.finish-payment', compact('destination', 'selectedImage', 'result', 'encrypted_id'));
    }

    private function generateInvoiceNumber($formattedDate)
    {
        // ✅ Get a unique timestamp-based number
        $uniqueNumber = time(); // ✅ Uses the current Unix timestamp

        // ✅ Format Invoice Number: INV-YYYYMMDD-TIMESTAMP
        return sprintf("INV-%s-%d", Carbon::parse($formattedDate)->format("Ymd"), $uniqueNumber);
    }

    private function generateTicketCode()
    {
        $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz'; // ✅ Alphanumeric mix
        $code = '';

        for ($i = 0; $i < 6; $i++) {
            $code .= $characters[rand(0, strlen($characters) - 1)]; // ✅ Random selection
        }

        return $code;
    }

    public function finishPaymentPage()
    {
        // $ticketOrder = TicketOrder::with('details')->findOrFail($id);
        $destination = Destination::with('images')->orderBy('id', 'asc')->first();
        $selectedImage = $destination && $destination->images->isNotEmpty()
            ? asset('storage/destination/' . basename($destination->images->first()->image_url))
            : asset('storage/destination/bg-booking-header.png');

        return view('landing-page.finish-payment', compact('destination', 'selectedImage'));
    }

    public function invoice()
    {
        return view('invoice');
    }

    public function downloadInvoice($id)
    {
        $id = Crypt::decrypt($id);
        // dd($id);
        //get ticket order by id
        $ticketOrder = TicketOrder::findOrFail($id);

        if (!$ticketOrder) {
            return 404; // ❌ Not Found
        }

        $items = DB::table('ticket_order_details AS tod')
            ->join('guest_types AS gt', 'gt.id', '=', 'tod.guest_type_id')
            ->select(
                'tod.day_type',
                'gt.name AS guest_type',
                DB::raw('SUM(tod.qty) AS total_qty'),
                DB::raw('SUM(tod.total_price) AS total_price')
            )
            ->where('tod.order_id', $id)
            ->groupBy('gt.name', 'tod.day_type')
            ->get();



        // ✅ Retrieve the invoice HTML view
        $data = [
            'invoice_number' => $ticketOrder->billing_number,
            'created_date' => Carbon::parse($ticketOrder->created_at)->format('d F Y'),
            'company' => 'PT LocalVenture Tourism',
            'address' => 'Jl. Utama, No. 1. Kota Besar',
            'customer_name' => $ticketOrder->visitor_name,
            'customer_email' => $ticketOrder->visitor_email,
            'customer_phone' => $ticketOrder->visitor_phone,
            'items' => $items,
            'total_price' => number_format($ticketOrder->total_price, 2, ',', '.'),
            'payment_status' => $ticketOrder->payment_status,
            'purchasing_type' => $ticketOrder->purchasing_type,
            'payment_type' => $ticketOrder->paymentType->payment_type_name,
        ];

        $imagePath = public_path('booking/images/logo.png');
        $base64Image = $this->processImage($imagePath);

        $base64ImageQRIS = null;
        $bank_info = null;

        if ($ticketOrder->paymentType->payment_type_name == 'QRIS') {
            $qris = DB::table('payment_type')
                ->select('id', 'payment_type_name', 'payment_image')
                ->where('id', 1)
                ->whereNull('deleted_at')
                ->first();

            $imagePath = public_path('storage/' . $qris->payment_image);
            $base64ImageQRIS = $this->processImage($imagePath);
        } else {
            $bank_= DB::table('bank_accounts')->where('id', $ticketOrder->bank_id )->first();
            $bank_info = $bank_->bank_name . ' - ' . $bank_->account_number . ' a.n. ' . $bank_->account_name;
        }

        $data['bank_info'] = $bank_info;

        $qrcodeBase64 = base64_encode(QrCode::format('png')->size(200)->generate($ticketOrder->billing_number));

        $pdf = Pdf::loadView('invoice', $data, [
            'base64Image' => $base64Image,
            'base64ImageQRIS' => $base64ImageQRIS,
            'qrcodeBase64' => $qrcodeBase64
        ]);

        $filename = 'Invoice - ['.$ticketOrder->total_visitor.' visitor] - '.$ticketOrder->destination->name.' - ' .$ticketOrder->billing_number. '.pdf';


        Pdf::setOption(['isRemoteEnabled' => true]);
        return $pdf->download($filename);
    }

    private function processImage($imagePath)
    {
        if (!file_exists($imagePath)) {
            // Return a 1-pixel transparent image as fallback
            return 'iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNkYAAAAAYAAjCB0C8AAAAASUVORK5CYII=';
        }

        // Read file in chunks to avoid memory issues
        $handle = fopen($imagePath, 'rb');
        $contents = '';
        while (!feof($handle)) {
            $contents .= fread($handle, 8192); // Read 8KB at a time
        }
        fclose($handle);

        return base64_encode($contents);
    }

    public function cek(Request $request, $billing = null)
    {

        if ($billing == null) {
            return redirect()->route('landing-page.home');
        }

        // ✅ Get transaction if billing is provided
        $transaction = $billing
            ? TicketOrder::where('billing_number', $billing)->first()
            : null;

        if (!$transaction) {
            return redirect()->route('landing-page.home');
        }

        $img_width = '100%';

        // ✅ Determine image source
        if (!$transaction) {
            // If billing is null, get random image from destination_gallery
            $selectedImage = DestinationGallery::inRandomOrder()->first()->file_name ?? asset('assets/image/no-img-square.png');
        } else {
            // If billing exists, get destination image based on transaction's destination_id
            $destination = Destination::where('id', $transaction->destination_id)->with('images')->first();
            $selectedImage = $destination && $destination->images->isNotEmpty()
                ? asset('storage/destination/' . basename($destination->images->first()->image_url))
                : asset('assets/image/no-img-square.png');

            $img_width = $destination && $destination->images->isNotEmpty()
                ? '100%'
                : '300px';
        }

        $destination = Destination::with('images')
            ->where('id', $transaction->destination_id)
            ->orderBy('id', 'asc')->first();

        $confirmation = null;

        $confirmationData = PaymentConfirmation::where('billing_number', $billing)
            ->orderBy('created_at', 'desc')
            ->limit(1)->get();
        if ($confirmationData) {
            $confirmation = $confirmationData;
        }

        // dd($confirmation);
        $encrypted_id = Crypt::encrypt($transaction->id);

        return view('landing-page.cek', compact('transaction', 'selectedImage', 'billing', 'destination', 'confirmation', 'img_width', 'encrypted_id'));
    }


    public function storeTicketPurchase(Request $request)
    {
        $validatedData = $request->validate([
            'destination_id' => 'required|exists:destinations,id',
            'total_qty' => 'required|numeric|min:1',
            'total_price' => 'required|numeric|min:0',
            'ticket_details' => 'required|array', // ✅ Ensures ticket_details is an array
            'ticket_details.*.qty' => 'required|numeric|min:0', // ✅ Validates each ticket type
            'ticket_details.*.price' => 'required|numeric|min:0', // ✅ Ensures valid prices
        ]);

        try {
            DB::beginTransaction(); // ✅ Ensures atomicity

            $formattedDate = Carbon::now()->format('Ymd');

            $object = [
                'destination_id' => $validatedData['destination_id'],
                'visitor_type' => ($validatedData['total_qty'] > 1) ? 'group' : 'individual',
                'visit_date' => $formattedDate,
                'total_visitor' => $validatedData['total_qty'],
                'total_price' => $validatedData['total_price'],
                'billing_number' => $this->generateInvoiceNumber($formattedDate),
                'payment_status' => 'received',
                'purchasing_type' => 'onsite',
                'notes' => null,
                'created_by' => auth()->id(),
                'visitor_male_count' => 0,
                'visitor_female_count' => 0,
                'payment_type_id' => 4,
            ];

            // ✅ Create Ticket Order
            $ticketOrder = TicketOrder::create($object);

            // ✅ Define Guest Types
            $guestTypes = ['anak-anak', 'dewasa', 'mancanegara'];

            // ✅ Determine day type
            $dayType = (Carbon::parse($formattedDate)->isWeekend()) ? 'weekend' : 'weekday';

            foreach ($guestTypes as $guestTypeName) {
                // Access quantity and price from the request data
                $qty = $validatedData['ticket_details'][$guestTypeName]['qty'] ?? 0;
                $price = $validatedData['ticket_details'][$guestTypeName]['price'] ?? 0;

                $guestTypeId = DB::table('guest_types')->where('name', $guestTypeName)->value('id');
                // ✅ Retrieve Guest Type ID
                $guestTypeId = DB::table('guest_types')->where('name', $guestTypeName)->value('id');

                // ✅ Determine day type
                $dayType = (Carbon::parse($formattedDate)->isWeekend()) ? 'weekend' : 'weekday';

                // ✅ Retrieve pricing
                $pricing = DB::table('pricing')
                    ->where('destination_id', $validatedData['destination_id'])
                    ->where('guest_type_id', $guestTypeId)
                    ->where('day_type', $dayType)
                    ->first();


                if ($pricing) {
                    $insurancePrice = $pricing->insurance_price ?? 0;
                    $basePrice = $pricing->base_price;
                    $totalPrice = $insurancePrice + $basePrice;

                    // ✅ Store each guest type individually
                    for ($i = 0; $i < $qty; $i++) {
                        TicketOrderDetail::create([
                            'ticket_code' => $this->generateTicketCode(), // ✅ Generate unique ticket code
                            'order_id' => $ticketOrder->id,
                            'guest_type_id' => $guestTypeId,
                            'day_type' => $dayType,
                            'visit_date' => $formattedDate,
                            'insurance_price' => $insurancePrice,
                            'base_price' => $basePrice,
                            'total_price' => $totalPrice,
                            'qty' => 1, // ✅ Always store individual tickets per row
                            'created_by' => auth()->id(),
                        ]);
                    }
                }
            }

            DB::commit(); // ✅ Confirm transaction

            return response()->json([
                'message' => 'Booking created successfully!',
                'data' => [],
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack(); // ❌ Rollback on error
            return response()->json([
            'message' => 'Error processing booking!',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function downloadTicketBaru(Request $request, $id)
    {
        $id = Crypt::decrypt($id);
        $ticketOrder = TicketOrder::with('destination', 'paymentType')
            ->where('id', $id)->first();
        $filename = 'e-Ticket - ['.$ticketOrder->total_visitor.' visitor] - '.$ticketOrder->destination->name.' - ' .$ticketOrder->billing_number. '.pdf';

        if($ticketOrder->payment_status !== 'paid' && $ticketOrder->payment_status !== 'received') {
            return response()->json(['error' => 'Payment not completed'], 403);
        }

        $ticketOrderDetails = TicketOrderDetail::where('order_id', $id)->get();
        foreach ($ticketOrderDetails as $ticketOrderDetail) {
            $ticketOrderDetail->qrcode = base64_encode(QrCode::format('svg')->size(200)
            ->color(26, 55, 77) // 🔹 Converted hex #1a374d to RGB (26, 55, 77)
            ->backgroundColor(255, 255, 255)
            ->errorCorrection('H')->generate($ticketOrderDetail->ticket_code));
        }

        $imagePath = public_path("storage/assets/image/ticket-bg.png");
        $base64Image = $this->processImage($imagePath);

        $data = [
            'title' => $ticketOrder->destination->name,
            'ticketOrderDetails' => $ticketOrderDetails,
            'base64Image' => $base64Image,
            // 'destination_image' => $this->processImage(public_path("storage/destination/" . $ticketOrder->destination->images[0]->filename)),
        ];

        $pdf = Pdf::loadView('pdf_template', $data)
            ->setPaper('A6', 'portrait');

        Pdf::setOption(['isRemoteEnabled' => true]);

        return $pdf->download($filename);
    }

    public function updateCheckIn(Request $request)
    {
        try {
            DB::beginTransaction();

            // 🔍 Step 1: Find the ticket by code
            $ticket = DB::table('ticket_order_details')
                ->where('ticket_code', $request->ticket_code)
                ->first();

            if (!$ticket) {
                DB::rollBack();
                return response()->json(['message' => 'Ticket not found.'], 404);
            }

            // 🚫 Step 2: Check if already checked in
            if ($ticket->check_in_at || $ticket->check_in_by) {
                DB::rollBack();
                return response()->json(['message' => 'Ticket already checked in at '. \Carbon\Carbon::parse($ticket->check_in_at)->format('d-m-Y H:i:s')], 409);
            }

            // ✅ Step 3: Update check-in
            DB::table('ticket_order_details')
                ->where('id', $ticket->id)
                ->update([
                    'check_in_at' => now(),
                    'check_in_by' => Auth::id(),
                ]);

            DB::commit();

            return response()->json([
                'message' => 'Check-in updated successfully at ' . now()->format('d-m-Y H:i:s')
            ], 200);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Error updating check-in!',
                'error' => $e->getMessage()
            ], 500);
        }
    }




}
