<?php

namespace App\Http\Controllers\landingpage;

use App\Http\Controllers\Controller;
use App\Models\Destination;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $destinations = Destination::with('images')->orderBy('id', 'asc')->get(); // ✅ Order by ID ascending
        $destinationNames = $destinations->pluck('name')->toArray();

        return view('landing-page.home', compact('destinations', 'destinationNames'));
    }


}
