<?php

namespace App\Http\Controllers;

use App\Models\OperatorTransaction;
use App\Models\OperatorTransactionDetail;
use App\Models\TicketOrder;
use App\Models\TicketOrderDetail;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use DOMDocument;
use DOMXPath;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Reader\Xls\RC4;
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
        // $transactions = TicketOrder::all();
        return view('admin.transaction.index');
    }

    public function getData(Request $request)
    {
        if ($request->ajax()) {

            if(auth()->user()->role_id == 1){
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
            } else if(auth()->user()->role_id == 2){
                $query = TicketOrder::join('destinations', 'ticket_orders.destination_id', '=', 'destinations.id')
                ->join('payment_type', 'ticket_orders.payment_type_id', '=', 'payment_type.id')
                ->join('user_mapping', 'ticket_orders.destination_id', '=', 'user_mapping.destination_id')
                ->where('ticket_orders.purchasing_type', 'online')
                ->where('user_mapping.user_id', auth()->user()->id)
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
            }

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

        if (!is_user_mapped(auth()->id(), $transaction->destination_id)) {
            return abort(403);
        }

        if ($transaction->payment_type_id == 3) {
            $transaction = TicketOrder::with('destination', 'paymentType')
                ->leftJoin('bank_accounts', 'ticket_orders.bank_id', '=', 'bank_accounts.id')
                ->select('ticket_orders.*', 'bank_accounts.bank_name',  'bank_accounts.account_name', 'bank_accounts.account_number') // ✅ Fetch bank details
                ->findOrFail($id);
        }

        $transactionDetail = TicketOrderDetail::with('guestType')->where('order_id', $id)->get();

        return view('admin.transaction.detail', compact('transaction', 'transactionDetail'));
    }

    public function detailOperator($billing)
    {
        $transaction = TicketOrder::with('destination', 'paymentType')->where('billing_number', $billing)->firstOrFail();

        $transactionDetail = TicketOrderDetail::with('guestType')->where('order_id', $transaction->id)->get();

        return view('admin.transaction.cek-detail', compact('transaction', 'transactionDetail'));
    }

    public function transactionOnsite()
    {
        return view('admin.transaction.index-onsite');
    }

    public function getDataOnsite(Request $request)
    {
        if ($request->ajax()) {
            if(auth()->user()->role_id == 1){
                $query = TicketOrder::join('destinations', 'ticket_orders.destination_id', '=', 'destinations.id')
                ->join('payment_type', 'ticket_orders.payment_type_id', '=', 'payment_type.id')
                ->where('ticket_orders.purchasing_type', 'onsite')
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
            } else if (auth()->user()->role_id == 2) {
                $query = TicketOrder::join('destinations', 'ticket_orders.destination_id', '=', 'destinations.id')
                ->join('user_mapping', 'ticket_orders.destination_id', '=', 'user_mapping.destination_id')
                ->join('payment_type', 'ticket_orders.payment_type_id', '=', 'payment_type.id')
                ->where('user_mapping.user_id', auth()->user()->id)
                ->where('ticket_orders.purchasing_type', 'onsite')
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
            }

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
                    return '<a href="' . route('admin.transaction-onsite.detail', $row->id) . '" class="btn btn-sm btn-primary">View</a>';
                })
                ->rawColumns(['action', 'payment_status'])
                ->orderColumn('DT_RowIndex', function ($query, $order) {
                    $query->orderBy('ticket_orders.created_at', 'desc');
                })
                ->make(true);
        }

        return response()->json(['error' => 'Invalid request'], 400);
    }

    public function detailOnSite($id)
    {
        $transaction = TicketOrder::with('destination', 'paymentType')->findOrFail($id);

        if (!is_user_mapped(auth()->id(), $transaction->destination_id)) {
            return abort(403);
        }

        $transactionDetail = TicketOrderDetail::where('order_id', $transaction->id)->get();
        return view('admin.transaction.detail', compact('transaction', 'transactionDetail'));
    }

    public function downloadTicket(Request $request)
    {
        // dd($request->billing);

        $transactionDetail = TicketOrderDetail::with('guestType')->where('ticket_code', $request->billing)->first();
        if(!$transactionDetail){
            return abort(404);
        }

        $ticketOrder = TicketOrder::with('destination', 'paymentType')
        ->where('id', $transactionDetail->order_id)->first();

        return view('admin.transaction.ticket', compact('transactionDetail', 'ticketOrder'));
        // $html = view('admin.transaction.ticket')->render();

        // // ✅ Extract only the div with class 'ticket-wrap'
        // $dom = new DOMDocument();
        // @$dom->loadHTML($html);
        // $xpath = new DOMXPath($dom);
        // $ticketWrap = $xpath->query("//div[contains(@class, 'ticket-wrap')]")->item(0);

        // if ($ticketWrap) {
        //     $pdf = Pdf::loadHTML($ticketWrap->C14N()) // ✅ Converts only the grey box into PDF
        //         ->setPaper('A5', 'portrait'); // ✅ Fits thermal print width

        //     return $pdf->download('ticket.pdf');
        // }

        // return response()->json(['error' => 'Ticket content not found'], 404);
    }

    public function history(Request $request)
    {
        // ✅ Validate `daterange` format
        $request->validate([
            'daterange' => [
                'regex:/^\d{4}-\d{2}-\d{2} - \d{4}-\d{2}-\d{2}$/', // ✅ Strict format: YYYY-MM-DD - YYYY-MM-DD
            ]
        ]);

        // ✅ Parse the date range from the request
        $dateRange = explode(' - ', $request->daterange ?? '');
        $startDate = isset($dateRange[0]) ? Carbon::parse($dateRange[0])->startOfDay() : Carbon::now()->subDays(7)->startOfDay();
        $endDate = isset($dateRange[1]) ? Carbon::parse($dateRange[1])->endOfDay() : Carbon::now()->endOfDay();

        // ✅ Fetch filtered transactions
        $transactions = OperatorTransaction::leftJoin('operator_transaction_detail', 'operator_transaction.id', '=', 'operator_transaction_detail.operator_transaction_id')
            ->leftJoin('destinations', 'operator_transaction.destination_id', '=', 'destinations.id')
            ->select(
                'operator_transaction.*',
                'destinations.name AS name',
                DB::raw('COUNT(operator_transaction_detail.id) AS total_details')
            )
            ->where('operator_transaction.created_by', auth()->id())
            ->whereBetween('operator_transaction.created_at', [$startDate, $endDate]) // ✅ Apply date filter
            ->groupBy('operator_transaction.id', 'destinations.name')
            ->paginate(10)
            ->appends($request->query());

        return view('admin.home.history', compact('transactions'));
    }


    public function delete(Request $request, $id)
    {
        try {
            $ticketOrder = TicketOrder::findOrFail($id);
            $ticketOrder->delete(); // ✅ Soft deletes the record

            return response()->json(['message' => 'Transaction deleted successfully!'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error deleting transaction', 'error' => $e->getMessage()], 500);
        }
    }

    public function OperatorBilling(Request $request)
    {
        $transactions = TicketOrder::with(['destination', 'groupedDetails'])
        ->where('created_by', auth()->id())
        ->where('purchasing_type', 'onsite')
        ->where('payment_status', 'received')
        ->whereNotIn('id', function($subquery) {
            $subquery->select('ticket_order_id')
                    ->from('operator_transaction_detail');
        })
        ->orderBy('created_at', 'desc') // ✅ Must be before `get()`
        ->get();

        // $transactions = $query->orderBy('created_at', 'desc')->get();
        return view('admin.home.operator-billing', compact('transactions'));
    }

    public function createBilling(Request $request)
    {
        try {
            DB::beginTransaction(); // ✅ Start transaction for atomic operations

            // ✅ Generate unique billing number
            $billingNumber = 'OT-' . now()->format('YmdHis') . rand(1000, 9999);

            // ✅ Calculate total ticket orders & total amount
            $totalOrders = count($request->selected_transactions);
            $totalAmount = TicketOrder::whereIn('id', $request->selected_transactions)->sum('total_price');

            $operatorTransaction = OperatorTransaction::create([
                'billing_number' => $billingNumber,
                'total_ticket_order' => $totalOrders,
                'total_amount' => $totalAmount,
                'transfer_amount' => null, // Transfer amount will be updated later
                'created_by' => auth()->id(),
            ]);

            $destinationId = null; // ✅ Store the first destination_id

            foreach ($request->selected_transactions as $ticketOrderId) {
                $ticketOrder = TicketOrder::find($ticketOrderId);
                OperatorTransactionDetail::create([
                    'operator_transaction_id' => $operatorTransaction->id,
                    'ticket_order_id' => $ticketOrderId,
                    'qty' => 1, // Assuming each ticket order counts as 1
                    'amount' => $ticketOrder->total_price,
                    'created_by' => auth()->id(),
                ]);

                if (!$destinationId) {
                    $destinationId = $ticketOrder->destination_id; // ✅ Assign only the first destination_id
                }
            }

            // ✅ Perform the update **AFTER** the loop completes
            DB::table('operator_transaction')
            ->where('id', $operatorTransaction->id)
            ->update([
                'destination_id' => $destinationId
            ]);


            DB::commit(); // ✅ Commit transaction

            return response()->json([
                'message' => 'Billing created successfully!',
                'billing_number' => $billingNumber,
                'total_ticket_order' => $totalOrders,
                'total_amount' => number_format($totalAmount, 0, ',', '.'),
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack(); // ✅ Rollback on error
            return response()->json([
                'message' => 'Error creating billing!',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function deleteOperatorBilling(Request $request)
    {
        try {
            DB::beginTransaction(); // ✅ Start transaction for safe deletion

            // ✅ Validate input
            $request->validate([
                'operator_transaction_id' => 'required|exists:operator_transaction,id',
            ]);

            // ✅ Delete all related details first (cascading behavior)
            OperatorTransactionDetail::where('operator_transaction_id', $request->operator_transaction_id)->delete();

            // ✅ Delete the transaction
            OperatorTransaction::where('id', $request->operator_transaction_id)->delete();

            DB::commit(); // ✅ Confirm deletion

            return response()->json([
                'message' => 'Transaction deleted successfully!',
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack(); // ✅ Undo changes on failure
            return response()->json([
                'message' => 'Error deleting transaction!',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function historyDetail(Request $request)
    {
        // ✅ Validate billing number format & existence
        $request->validate([
            'billing_number' => 'required|string|exists:operator_transaction,billing_number',
        ]);

        // ✅ Fetch transaction by billing number
        $operatorTransaction = OperatorTransaction::where('billing_number', $request->billing_number)
            ->leftJoin('destinations', 'operator_transaction.destination_id', '=', 'destinations.id')
            ->select('operator_transaction.*', 'destinations.name AS destination_name')
            ->first();

        // ✅ Redirect back if billing number does not exist
        if (!$operatorTransaction) {
            return redirect()->back()->with('error', 'Billing number not found!');
        }

        // ✅ Fetch related transaction details
        $transactionDetails = OperatorTransactionDetail::where('operator_transaction_id', $operatorTransaction->id)
            ->leftJoin('ticket_orders', 'operator_transaction_detail.ticket_order_id', '=', 'ticket_orders.id')
            ->select('operator_transaction_detail.*', 'ticket_orders.billing_number AS ticket_order_billing_number')
            ->get();

        return view('admin.transaction.history-billing-detail', compact('operatorTransaction', 'transactionDetails'));
    }


}
