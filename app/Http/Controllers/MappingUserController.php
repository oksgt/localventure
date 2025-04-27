<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MappingUserController extends Controller
{
    public function index()
    {
        return view('admin.mapping-user.index');
    }
}
