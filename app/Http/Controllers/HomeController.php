<?php

namespace App\Http\Controllers;

use App\Models\Destination;
use App\Models\UserMapping;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function index()
    {

        if (session('role_id') == 1) {
            $destinations = Destination::all();
            return view('admin.home.index', compact('destinations'));
        } else {

            $userMapping = UserMapping::where('user_id', Auth::id())->first();
            $destinations = Destination::where('id', $userMapping->destination_id)->get();
            return view('admin.home.index_operator', compact('destinations'));
        }
    }

    public function getVisitorDataChart(Request $request)
    {
        $start = $request->input('start');
        $end = $request->input('end');
        $destinationId = $request->input('destination_id');

        $totalRevenue = 0;

        $destinations = Destination::where('id', $destinationId)->first();

        $data = DB::table('ticket_order_details as tod')
            ->select(
                DB::raw('SUM(tod.qty) as qty, SUM(tod.total_price) as total_price, DATE_FORMAT(tod.visit_date, "%d %M %Y") as visit_date'),
                'd.name',
                'to2.destination_id'
            )
            ->join('ticket_orders as to2', 'to2.id', '=', 'tod.order_id')
            ->join('destinations as d', 'to2.destination_id', '=', 'd.id')
            ->whereBetween('tod.visit_date', [$start, $end]);

        // Filter by destination_id if provided
        if ($destinationId) {
            $data->where('to2.destination_id', $destinationId);
        }

        $dataDetail = DB::table('ticket_order_details as tod')
            ->select(
                DB::raw('CONCAT(UPPER(SUBSTRING(gt.name, 1, 1)), LOWER(SUBSTRING(gt.name, 2))) AS name'),
                'tod.guest_type_id',
                DB::raw('SUM(qty) AS qty'),
                DB::raw('SUM(tod.total_price) AS total_price')
            )
            ->join('ticket_orders as to2', 'to2.id', '=', 'tod.order_id')
            ->join('destinations as d', 'to2.destination_id', '=', 'd.id')
            ->join('guest_types as gt', 'gt.id', '=', 'tod.guest_type_id')
            ->where('to2.destination_id', $destinationId)
            ->whereBetween('tod.visit_date', [$start, $end])
            ->groupBy('tod.guest_type_id', 'name') // Use the alias 'name' here
            ->get();

        $result['visitorDetail'] = $dataDetail;

        $data = $data->groupBy('tod.visit_date', 'd.name', 'to2.destination_id')
            ->orderBy('tod.visit_date')
            ->get();

        $result['visitorCount'] = $data;

        foreach ($data as $item) {
            $totalRevenue += $item->total_price;
        }

        $result['totalRevenue'] = $totalRevenue;
        $result['destinations'] = $destinations->name;

        $startDate = Carbon::parse($start);
        $endDate = Carbon::parse($end);

        $result['selectedPeriod'] = $startDate->format('d F Y') . ' - ' . $endDate->format('d F Y');

        $PurchasingSalesPieChart = DB::table('ticket_orders as to2')
            ->select('to2.destination_id', 'to2.purchasing_type', DB::raw('SUM(to2.total_price) AS total'))
            ->where('to2.payment_status', 'paid')
            ->whereBetween('to2.visit_date', [$start, $end])
            ->where('to2.destination_id', $destinationId)
            ->groupBy('to2.destination_id', 'to2.purchasing_type') // Include destination_id in GROUP BY
            ->get();

        $result['PurchasingSalesPieChart']['data'] = $PurchasingSalesPieChart;

        return response()->json($result);
    }

    public function ticketPurchase()
    {
        $userMapping = UserMapping::where('user_id', Auth::id())->first();

        $destinations = Destination::with('images')->where('id', $userMapping->destination_id)->get();
        return view('admin.home.ticket_purchase', compact('destinations'));
    }
}
