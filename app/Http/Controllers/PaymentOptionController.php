<?php

namespace App\Http\Controllers;

use App\Models\PaymentType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;


class PaymentOptionController extends Controller
{
    public function index()
    {
        return view('admin.settings.payment.index');
    }

    // ✅ Fetch data for Datatable
    public function getData()
    {
        $query = PaymentType::select('id', 'payment_type_name', 'payment_image', 'status');

        return DataTables::of($query)
            ->addColumn('action', function ($paymentType) {
                return '<button class="btn btn-warning btn-sm edit-btn" data-id="' . $paymentType->id . '"><i class="fa fa-edit"></i>Edit</button>
                        <button class="btn btn-danger btn-sm delete-btn" data-id="' . $paymentType->id . '"><i class="fa fa-trash"></i>Delete</button>';
            })
            ->rawColumns(['action']) // ✅ Ensures buttons render correctly
            ->make(true);
    }

    // ✅ Store new payment type
    public function store(Request $request)
    {
        // ✅ Define custom validation messages
        $messages = [
            'payment_type_name.required' => 'The payment type name is required.',
            'payment_type_name.string' => 'The payment type name must be a valid text string.',
            'payment_type_name.max' => 'The payment type name cannot exceed 255 characters.',
            'payment_image.image' => 'The file must be a valid image (jpeg, png, jpg, gif, svg).',
            'payment_image.mimes' => 'Only jpeg, png, jpg, gif, and svg formats are allowed.',
            'payment_image.max' => 'The image size must be less than 2MB.',
            'status.required' => 'Status is required.',
            'status.integer' => 'Status must be a valid number (0 or 1).',
        ];

        // ✅ Validate input fields with custom messages
        $validator = Validator::make($request->all(), [
            'payment_type_name' => 'required|string|max:255',
            'payment_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'status' => 'required|integer',
        ], $messages); // ✅ Pass custom messages

        // ✅ Handle validation errors
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        // ✅ Upload image if provided
        $imagePath = null;
        if ($request->hasFile('payment_image')) {
            $imagePath = $request->file('payment_image')->storeAs(
                'payment_option', // ✅ Ensure it targets the correct directory
                $request->file('payment_image')->getClientOriginalName(),
                'public'
            );
        }

        // ✅ Store the new payment type
        PaymentType::create([
            'payment_type_name' => $request->payment_type_name,
            'payment_image' => $imagePath,
            'status' => $request->status,
            'created_by' => auth()->id(),
        ]);

        return response()->json(['message' => 'Payment type added successfully']);
    }

    // ✅ Edit payment type
    public function edit($id)
    {
        $paymentType = PaymentType::findOrFail($id);
        return response()->json($paymentType);
    }

    // ✅ Update payment type
    public function update(Request $request, $id)
    {
        $paymentType = PaymentType::findOrFail($id);

        $messages = [
            'payment_type_name.required' => 'The payment type name is required.',
            'payment_type_name.string' => 'The payment type name must be a valid text string.',
            'payment_type_name.max' => 'The payment type name cannot exceed 255 characters.',
            'payment_image.image' => 'The file must be a valid image (jpeg, png, jpg, gif, svg).',
            'payment_image.mimes' => 'Only jpeg, png, jpg, gif, and svg formats are allowed.',
            'payment_image.max' => 'The image size must be less than 2MB.',
            'status.required' => 'Status is required.',
            'status.integer' => 'Status must be a valid number (0 or 1).',
        ];

        $validator = Validator::make($request->all(), [
            'payment_type_name' => 'required|string|max:255',
            'payment_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'status' => 'required|integer',
        ], $messages);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        // ✅ Update image only if a new one is uploaded
        if ($request->hasFile('payment_image')) {
            if ($paymentType->payment_image) {
                Storage::disk('public')->delete($paymentType->payment_image); // ✅ Remove old file
            }

            $paymentType->payment_image = $request->file('payment_image')->storeAs(
                'payment_option',
                $request->file('payment_image')->getClientOriginalName(),
                'public'
            );
        }

        $paymentType->payment_type_name = $request->payment_type_name;
        $paymentType->status = $request->status;
        $paymentType->updated_by = auth()->id();
        $paymentType->save();

        return response()->json(['message' => 'Payment type updated successfully']);
    }

    // ✅ Delete payment type
    public function destroy($id)
    {
        $paymentType = PaymentType::findOrFail($id);
        $paymentType->delete();

        return response()->json(['message' => 'Payment type deleted successfully']);
    }
}
