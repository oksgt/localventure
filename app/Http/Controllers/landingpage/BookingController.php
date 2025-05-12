<?php

namespace App\Http\Controllers\landingpage;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Destination;
use App\Models\PaymentType;
use App\Models\Pricing;
use App\Models\TicketOrder;
use App\Models\TicketOrderDetail;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Session;

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


            // ✅ Create Ticket Order
            $ticketOrder = TicketOrder::create([
                'destination_id' => $validatedData['formData']['selectDestinationId'],
                'visitor_type' => ($validatedData['formData']['people_count'] > 1) ? 'group' : 'individual',
                'visit_date' => $formattedDate,
                'visitor_name' => $validatedData['formData']['name'],
                'visitor_address' => $validatedData['formData']['address'],
                'visitor_phone' => $validatedData['formData']['phone'],
                'visitor_origin_description' => $validatedData['formData']['origin'] ?? null,
                'visitor_email' => $validatedData['formData']['email'] ?? null,
                'total_visitor' => $validatedData['formData']['anak-anak'] + $validatedData['formData']['dewasa'] + $validatedData['formData']['mancanegara'],
                'total_price' => $validatedData['formData']['total_price'],
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
                'bank_id' => null,
            ]);

            // ✅ Define Guest Types
            $guestTypes = ['anak-anak', 'dewasa', 'mancanegara'];

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
                        }
                    }
                }
            }

            DB::commit(); // ✅ Confirm transaction

            return response()->json([
                'message' => 'Booking created successfully!',
                'ticket_order_id' => $ticketOrder->id
            ], 200);

        } catch (\Exception $e) {
            DB::rollBack(); // ❌ Rollback on error
            return response()->json([
                'message' => 'Error processing booking!',
                'error' => $e->getMessage()
            ], 500);
        }
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
}
