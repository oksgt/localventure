<?php

namespace App\Http\Controllers\landingpage;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PaymentConfirmation;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class PaymentConfirmationController extends Controller
{
    public function store(Request $request)
    {
        // ✅ Validate request with custom Bahasa Indonesia error messages
        $validator = Validator::make($request->all(), [
            'ticket_order_id' => 'required|integer',
            'billing_number' => 'required|string',
            'transfer_amount' => 'required|numeric',
            'bank_name' => 'required|string',
            'account_name' => 'required|string',
            'account_number' => 'required|string',
            'image' => 'required|image|mimes:jpeg,png,jpg|max:5120',
        ], [
            'ticket_order_id.required' => 'Ticket order ID harus diisi.',
            'billing_number.required' => 'Invoice number harus diisi.',
            'transfer_amount.required' => 'Jumlah transfer harus diisi.',
            'transfer_amount.numeric' => 'Jumlah transfer harus berupa angka.',
            'image.image' => 'File harus berupa gambar.',
            'image.mimes' => 'Format gambar harus JPG, JPEG, atau PNG.',
            'image.max' => 'Ukuran gambar tidak boleh lebih dari 5MB.'
        ]);

        // ✅ Return validation errors
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // ✅ Handle file upload
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('bukti_bayar', 'public');
        } else {
            $imagePath = null;
        }

        // ✅ Save data
        PaymentConfirmation::create([
            'ticket_order_id' => $request->ticket_order_id,
            'billing_number' => $request->billing_number,
            'transfer_amount' => $request->transfer_amount,
            'bank_name' => $request->bank_name,
            'account_name' => $request->account_name,
            'account_number' => $request->account_number,
            'image' => $imagePath,
        ]);

        return response()->json(['message' => 'Konfirmasi pembayaran berhasil disimpan!'], 200);
    }

    public function show(Request $request)
    {
        $payment = PaymentConfirmation::where('billing_number', $request->billing_number)->get();

        if ($payment->isEmpty()) { // ✅ Corrected check for empty results
            return response()->json(['message' => 'Data tidak ditemukan'], 404);
        }

        return response()->json(['data' => $payment], 200);
    }

    public function updatePayment(Request $request)
    {
        DB::beginTransaction();

        try {
            // ✅ Validate request
            $request->validate([
                'id' => 'required|exists:payment_confirmation,id',
                'status' => 'required|in:0,1,2',
            ]);

            // ✅ Find payment confirmation record
            $payment = PaymentConfirmation::findOrFail($request->id);

            // ✅ Map status values
            $statusMap = [
                0 => 'pending',
                1 => 'paid',
                2 => 'rejected',
            ];

            // ✅ Update payment confirmation status
            $payment->status = $request->status;
            $payment->save();

            // ✅ Update related ticket_orders.payment_status
            DB::table('ticket_orders')
                ->where('id', $payment->ticket_order_id)
                ->update(['payment_status' => $statusMap[$request->status]]);

            DB::commit();
            return response()->json(['message' => 'Status berhasil diperbarui!'], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Terjadi kesalahan saat memperbarui status.'], 500);
        }
    }


}
