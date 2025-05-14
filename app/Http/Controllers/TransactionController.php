<?php

namespace App\Http\Controllers;

use App\Models\TicketOrder;
use App\Models\TicketOrderDetail;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $transactions = TicketOrder::all();
        return view('admin.transaction.index', compact('transactions'));
    }

    public function getData(Request $request)
    {
        if ($request->ajax()) {
            $query = TicketOrder::join('destinations', 'ticket_orders.destination_id', '=', 'destinations.id')
                ->join('payment_type', 'ticket_orders.payment_type_id', '=', 'payment_type.id')
                ->where('ticket_orders.purchasing_type', 'online')
                ->orderBy('ticket_orders.created_at', 'desc')
                ->select([
                    'ticket_orders.id',
                    'ticket_orders.visit_date',
                    'ticket_orders.billing_number',
                    'destinations.name AS destination_name', // ✅ Aliased column
                    'ticket_orders.visitor_name',
                    'ticket_orders.total_visitor',
                    'ticket_orders.notes',
                    'ticket_orders.total_price',
                    'payment_type.payment_type_name AS payment_name',
                    'ticket_orders.payment_status',
                    'ticket_orders.created_at'
                ]);

            return DataTables::of($query)
                ->filterColumn('destination_name', function ($query, $keyword) {
                    $query->where('destinations.name', 'like', "%{$keyword}%"); // ✅ Correct searching method
                })
                ->filterColumn('payment_name', function ($query, $keyword) {
                    $query->where('payment_type.payment_type_name', 'like', "%{$keyword}%"); // ✅ Correct searching for payment type
                })
                ->addIndexColumn()
                ->editColumn('total_price', function ($row) {
                    return 'Rp ' . number_format($row->total_price, 2, ',', '.');
                })
                ->editColumn('visit_date', function ($row) {
                    return Carbon::parse($row->visit_date)->format('d M Y');
                })
                ->editColumn('created_at', function ($row) {
                    return Carbon::parse($row->created_at)->format('d M Y H:i');
                })
                ->editColumn('payment_status', function ($row) {
                    return $row->payment_status === 'paid'
                        ? '<div class="badge badge-success">Paid</div>'
                        : '<div class="badge badge-warning">Pending</div>';
                })
                ->addColumn('action', function ($row) {
                    return '<a href="' . route('admin.transaction.detail', $row->id) . '" class="btn btn-sm btn-primary">View</a>';
                })
                ->rawColumns(['action', 'payment_status'])
                ->orderColumn('DT_RowIndex', function ($query, $order) {
                    $query->orderBy('ticket_orders.created_at', 'desc');
                })
                ->make(true);
        }

        return response()->json(['error' => 'Invalid request'], 400);
    }

    public function detail($id)
    {
        $transaction = TicketOrder::with('destination', 'paymentType')->findOrFail($id);

        if ($transaction->payment_type_id == 3) {
            $transaction = TicketOrder::with('destination', 'paymentType')
                ->leftJoin('bank_accounts', 'ticket_orders.bank_id', '=', 'bank_accounts.id')
                ->select('ticket_orders.*', 'bank_accounts.bank_name',  'bank_accounts.account_name', 'bank_accounts.account_number') // ✅ Fetch bank details
                ->findOrFail($id);
        }

        $transactionDetail = TicketOrderDetail::with('guestType')->where('order_id', $id)->get();

        return view('admin.transaction.detail', compact('transaction', 'transactionDetail'));
    }
}
