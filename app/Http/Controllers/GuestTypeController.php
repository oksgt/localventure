<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\GuestType;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class GuestTypeController extends Controller
{
    // Get all guest types for dropdown
    public function list()
    {
        $guestTypes = GuestType::select('id', 'name')->get();
        return response()->json(['success' => true, 'data' => $guestTypes]);
    }

}
