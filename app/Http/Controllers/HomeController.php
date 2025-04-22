<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index() {
        //print out all sessin data
        //dd(session()->all());
        return view('admin.home.index');
    }
}
