<?php

namespace App\Http\Controllers\landingpage;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Destination;
use App\Models\PaymentType;
use App\Models\Pricing;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;


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

        $pricing = DB::table('pricing')
        ->join('guest_types as gt', 'gt.id', '=', 'pricing.guest_type_id')
        ->select(
            'pricing.id',
            'pricing.destination_id',
            'pricing.guest_type_id',
            'gt.name as guest_name',
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

        if($pricing->isEmpty()){
            $searchData['pricing_available'] = false; // ✅ No pricing data found
        }

        $date = Carbon::createFromFormat('m/d/Y', $searchData['daterange']); // ✅ Correct parsing

        if ($date->isWeekend()) {
            $searchData['day_type'] = 'Weekend';
        } else {
            $searchData['day_type'] = 'Weekday';
        }

        $paymentTypes = PaymentType::where('status', 1)
            ->whereNull('deleted_at') // ✅ Ensure it's not deleted
            ->get(['id', 'payment_type_name as name']);

        $paymentTypesWithImage = PaymentType::where('status', 1)
            ->whereNull('deleted_at') // ✅ Ensure it's not deleted
            ->whereNotNull('payment_image') // ✅ Ensure image URL is not null
            ->get(['id', 'payment_image'])
            ->first();

        // dd($paymentTypesWithImage); // ✅ Debugging line to check search data

        return view('landing-page.booking',
        compact('searchData', 'destinations', 'destinationNames', 'selectedDestinationName', 'selectedImage', 'provinces', 'pricing', 'paymentTypes', 'paymentTypesWithImage')); // ✅ Pass data to results page
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


}
