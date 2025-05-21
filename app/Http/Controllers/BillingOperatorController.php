<?php

namespace App\Http\Controllers;

use App\Models\OperatorTransaction;
use App\Models\OperatorTransactionDetail;
use App\Models\TicketOrder;
use App\Models\TicketOrderDetail;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class BillingOperatorController extends Controller
{
    // Display a listing of the billing resources
    public function index()
    {
        return view('admin.billing.index');
    }

    public function billingHistory(Request $request)
    {
        if ($request->ajax()) {
            $transactions = OperatorTransaction::leftJoin('destinations', 'operator_transaction.destination_id', '=', 'destinations.id')
                ->leftJoin('users', 'operator_transaction.created_by', '=', 'users.id')
                ->select(
                    'operator_transaction.id',
                    'operator_transaction.billing_number',
                    'operator_transaction.total_ticket_order',
                    'operator_transaction.total_amount',
                    'operator_transaction.transfer_approval',
                    'operator_transaction.created_at',
                    'destinations.name AS destination_name',
                    'users.name AS created_by'
                );

            return DataTables::of($transactions)
                ->filterColumn('destination_name', function ($query, $keyword) {
                    $query->whereRaw("LOWER(destinations.name) LIKE ?", ["%{$keyword}%"]);
                })
                ->addColumn('status', function ($row) {
                    return $row->transfer_approval == 1
                        ? '<span class="badge badge-success">Paid</span>'
                        : '<span class="badge badge-warning">Unpaid</span>';
                })
                ->editColumn('total_amount', function ($row) {
                    return 'Rp ' . number_format($row->total_amount, 2, ',', '.');
                })
                ->editColumn('created_at', function ($row) {
                    return Carbon::parse($row->created_at)->format('d M Y H:i');
                })
                ->addColumn('action', function ($row) {
                    return '<a href="' . route('operator.billingOperatorDetail', ['id' => $row->id]) . '"
                        class="btn btn-sm btn-primary">View</a>';
                })
                ->rawColumns(['status', 'action']) // ✅ Allow HTML rendering
                ->make(true);
        }

        return view('admin.billing-history');
    }

    public function billingOperatorDetail(Request $request)
    {
        $operatorTransaction = OperatorTransaction::find($request->id);

        if (!$operatorTransaction) {
            return abort(404);
        }

        // ✅ Get ticket_order_ids from `operator_transaction_detail`
        $ticketOrderIds = OperatorTransactionDetail::where('operator_transaction_id', $request->id)
            ->pluck('ticket_order_id');

        // ✅ Fetch ticket_orders while avoiding ambiguous column names
        $ticketOrders = TicketOrder::whereIn('ticket_orders.id', $ticketOrderIds) // 🔹 Explicitly reference `ticket_orders.id`
            ->leftJoin('destinations', 'ticket_orders.destination_id', '=', 'destinations.id')
            ->select('ticket_orders.*', 'destinations.name AS destination_name')
            ->get();

        // ✅ Fetch related ticket_order_details
        $ticketOrderDetails = TicketOrderDetail::whereIn('order_id', $ticketOrders->pluck('id'))
            ->leftJoin('guest_types', 'ticket_order_details.guest_type_id', '=', 'guest_types.id')
            ->select('ticket_order_details.*', 'guest_types.name')
            ->get();

        dump($ticketOrders);
        dd($ticketOrderDetails);

        return view('admin.billing-operator-detail', compact('ticketOrders', 'ticketOrderDetails'));
    }
}
