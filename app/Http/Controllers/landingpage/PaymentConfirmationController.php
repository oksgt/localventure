<?php

namespace App\Http\Controllers\landingpage;

use App\Http\Controllers\Controller;
use App\Mail\PaymentConfirmed;
use Illuminate\Http\Request;
use App\Models\PaymentConfirmation;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

class PaymentConfirmationController extends Controller
{

    public function autoConfirmPayments()
    {
        // Simulate image upload path (make sure this image exists for test purposes)
        $dummyImagePath = storage_path('app/public/bukti_bayar/3uWzEBvySWXiQSZPnkhKPjHpuJK2EJr579zJwAmJ.jpg'); // Must be JPG ≤ 5MB

        if (!file_exists($dummyImagePath)) {
            return response()->json(['message' => 'Dummy image for upload not found.'], 404);
        }

        $banks = ['BCA', 'Mandiri', 'BRI', 'BNI', 'BTN', 'CIMB Niaga', 'Danamon', 'Permata', 'Panin', 'Bank Jateng'];

        $orders = DB::table('ticket_orders')
            ->where('id', '>=', 92)
            ->select('id', 'billing_number', 'total_price', 'visitor_name')
            ->get();

        $results = [];

        foreach ($orders as $order) {
            // Simulate a POST request payload
            $request = new \Illuminate\Http\Request();
            $request->replace([
                'ticket_order_id' => $order->id,
                'billing_number' => $order->billing_number,
                'transfer_amount' => $order->total_price,
                'bank_name' => $banks[array_rand($banks)],
                'account_name' => $order->visitor_name,
                'account_number' => (String) mt_rand(1000000000, 9999999999), // 10-digit fake bank account
            ]);

            // Attach a simulated UploadedFile instance
            $request->files->set('image', new UploadedFile(
                $dummyImagePath,
                'bukti.jpg',
                'image/jpeg',
                null,
                true
            ));

            // Call your original store method
            $response = $this->store($request);
            $results[] = [
                'ticket_order_id' => $order->id,
                'status' => $response->getStatusCode(),
                'message' => $response->getContent()
            ];
        }

        return response()->json([
            'message' => 'Simulasi penyimpanan otomatis selesai!',
            'results' => $results
        ]);
    }

    public function store(Request $request)
    {
        // ✅ Validate request with custom Bahasa Indonesia error messages
        $validator = Validator::make($request->all(), [
            'ticket_order_id' => 'required|integer',
            'billing_number' => 'required|string',
            'transfer_amount' => 'required',
            'bank_name' => 'required|string',
            'account_name' => 'required|string',
            'account_number' => 'required|string',
            'image' => 'required|image|mimes:jpeg,jpg|max:5120',
        ], [
            'ticket_order_id.required' => 'Ticket order ID harus diisi.',
            'billing_number.required' => 'Invoice number harus diisi.',
            'transfer_amount.required' => 'Jumlah transfer harus diisi.',
            'image.image' => 'File harus berupa gambar.',
            'image.mimes' => 'Format gambar harus JPG, JPEG. File yang Anda unggah adalah: ' . ($request->file('image') ? $request->file('image')->getClientOriginalExtension() : 'Tidak valid'),
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

        try {
            DB::beginTransaction();

            // ✅ Save data
            PaymentConfirmation::create([
                'ticket_order_id' => $request->ticket_order_id,
                'billing_number' => $request->billing_number,
                'transfer_amount' => str_replace('.', '', $request->transfer_amount),
                'bank_name' => $request->bank_name,
                'account_name' => $request->account_name,
                'account_number' => $request->account_number,
                'image' => $imagePath,
            ]);

            // ✅ Update related ticket_orders.payment_status
            DB::table('ticket_orders')
                ->where('id', $request->ticket_order_id)
                ->update(['payment_status' => 'received']);

            DB::commit();
            return response()->json(['message' => 'Konfirmasi pembayaran berhasil disimpan!'], 200);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json(['message' => 'Payment confirmation failed. Please try again.'], 500);
        }
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

            $ticket_orders_data = DB::table('ticket_orders')
                ->where('id', $payment->ticket_order_id)
                ->first();

            $encrypted_id = Crypt::encrypt($ticket_orders_data->id);

            if($request->status == 1){
                Mail::to($ticket_orders_data->visitor_email)->send(new PaymentConfirmed(
                    $ticket_orders_data->visitor_email,
                    $ticket_orders_data->billing_number,
                    route('invoice', $encrypted_id),
                    url('/download/ticket/baru', $encrypted_id)
                ));
            }

            DB::commit();
            return response()->json(['message' => 'Status berhasil diperbarui!'], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Terjadi kesalahan saat memperbarui status.' . $e->getMessage()], 500);
        }
    }


}
