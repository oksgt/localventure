<?php

namespace App\Http\Controllers;

use App\Models\Destination;
use Illuminate\Http\Request;
use App\Models\Wisata;

class DestinationController extends Controller
{
    // Display a listing of the resource.
    public function index()
    {
        return view('destination.index');
    }
}

