<?php

namespace App\Http\Controllers\landingpage;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PaymentConfirmation;
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
}
