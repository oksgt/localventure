<?php

namespace App\Http\Controllers\landingpage;

use App\Http\Controllers\Controller;
use App\Models\Destination;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $destinations = Destination::with('images')->orderBy('id', 'asc')->get();

        if($destinations->isEmpty()){
            return view('coming-page.index');
        }

        $destinationNames = $destinations->pluck('name')->toArray();

        // ✅ Get 1 random destination
        $randomDestination = Destination::with('images')->inRandomOrder()->first();

        return view('landing-page.home', compact('destinations', 'destinationNames', 'randomDestination'));
    }


}
