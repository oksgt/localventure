<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Wisata;

class DestinationController extends Controller
{
    // Display a listing of the resource.
    public function index()
    {
        $wisatas = Wisata::all();
        return view('wisata.index', compact('wisatas'));
    }
}

