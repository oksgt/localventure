<?php

namespace App\Http\Controllers;

use App\Models\Destination;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function index() {

        return view('admin.home.index');
    }

    public function getVisitorDataChart(Request $request)
    {
        $start = $request->input('start');
        $end = $request->input('end');

        $data = DB::table('ticket_order_details')
            ->select(DB::raw('SUM(qty) as qty, SUM(total_price) as total_price, DATE_FORMAT(visit_date, "%d %M %Y") as visit_date'))
            ->whereBetween('visit_date', [$start, $end])
            ->groupBy('visit_date')
            ->orderBy('visit_date')
            ->get();


        return response()->json($data);
    }
}
