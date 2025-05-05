<?php

namespace App\Http\Controllers\landingpage;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

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

        return view('landing-page.booking', compact('searchData')); // ✅ Pass data to results page
    }
}
