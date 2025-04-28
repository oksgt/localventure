<?php

namespace App\Http\Controllers;

use App\Models\Pricing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class MasterTicketController extends Controller
{
    public function index()
    {
        return view('admin.master-ticket.index');
    }

    public function getData()
    {
        $pricing = Pricing::select(
                'pricing.id',
                'destinations.name as destination_name',
                'guest_types.name as category',
                'pricing.base_price',
                'pricing.day_type',
                'pricing.insurance_price',
                'pricing.final_price'
            )
            ->join('destinations', 'destinations.id', '=', 'pricing.destination_id')
            ->join('guest_types', 'guest_types.id', '=', 'pricing.guest_type_id');

        return datatables()->of($pricing)
            ->addColumn('action', function ($row) {
                return '<button class="btn btn-sm btn-warning edit-pricing" data-id="' . $row->id . '">Edit</button>
                        <button class="btn btn-sm btn-danger delete-pricing" data-id="' . $row->id . '">Delete</button>';
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function store(Request $request)
    {

        $request->validate([
            'destination_id' => 'required|exists:destinations,id',
            'guest_type_id' => 'required|exists:guest_types,id',
            'day_type' => 'required|in:weekday,weekend',
            'base_price' => 'required|numeric|gt:0',
            'insurance_price' => 'nullable|numeric|gt:0',
        ], [
            'destination_id.required' => 'Please select a destination.',
            'destination_id.exists' => 'Selected destination is invalid.',

            'guest_type_id.required' => 'Please select a guest category.',
            'guest_type_id.exists' => 'Selected guest category is invalid.',

            'day_type.required' => 'Day type selection is required.',
            'day_type.in' => 'Day type must be either "weekday" or "weekend".',

            'base_price.required' => 'Base price is required.',
            'base_price.numeric' => 'Base price must be a valid number.',
            'base_price.gt' => 'Base price must be greater than 0.',

            'insurance_price.numeric' => 'Insurance price must be a valid number.',
            'insurance_price.gt' => 'Insurance price must be greater than 0.',
        ]);

        try {
            DB::beginTransaction();

            $basePrice = floatval(str_replace('.', '', $request->base_price));
            $insurancePrice = floatval(str_replace('.', '', $request->insurance_price));
            $finalPrice = $basePrice + $insurancePrice;

            Pricing::create([
                'destination_id' => $request->destination_id,
                'guest_type_id' => $request->guest_type_id,
                'day_type' => $request->day_type,
                'base_price' => $basePrice,
                'insurance_price' => $insurancePrice,
                'final_price' => $finalPrice,
                'created_by' => auth()->id(),
            ]);

            DB::commit();
            return response()->json(['success' => true, 'message' => 'Pricing added successfully']);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Error adding pricing: " . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Failed to add pricing', 'error' => $e->getMessage()], 500);
        }
    }

    public function edit($id)
    {
        $pricing = Pricing::findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $pricing->id,
                'destination_id' => $pricing->destination_id,
                'guest_type_id' => $pricing->guest_type_id,
                'day_type' => $pricing->day_type,
                'base_price' => $pricing->base_price,
                'insurance_price' => $pricing->insurance_price,
                'final_price' => $pricing->final_price,
            ]
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'destination_id' => 'required|exists:destinations,id',
            'guest_type_id' => 'required|exists:guest_types,id',
            'day_type' => 'required|in:weekday,weekend',
            'base_price' => 'required|numeric|gt:0',
            'insurance_price' => 'nullable|numeric|gt:0',
        ], [
            'destination_id.required' => 'Please select a destination.',
            'destination_id.exists' => 'Selected destination is invalid.',

            'guest_type_id.required' => 'Please select a guest category.',
            'guest_type_id.exists' => 'Selected guest category is invalid.',

            'day_type.required' => 'Day type selection is required.',
            'day_type.in' => 'Day type must be either "weekday" or "weekend".',

            'base_price.required' => 'Base price is required.',
            'base_price.numeric' => 'Base price must be a valid number.',
            'base_price.gt' => 'Base price must be greater than 0.',

            'insurance_price.numeric' => 'Insurance price must be a valid number.',
            'insurance_price.gt' => 'Insurance price must be greater than 0.',
        ]);

        try {
            DB::beginTransaction();

            $pricing = Pricing::findOrFail($id);

            $basePrice = floatval(str_replace('.', '', $request->base_price));
            $insurancePrice = floatval(str_replace('.', '', $request->insurance_price));
            $finalPrice = $basePrice + $insurancePrice;

            $pricing->update([
                'destination_id' => $request->destination_id,
                'guest_type_id' => $request->guest_type_id,
                'day_type' => $request->day_type,
                'base_price' => $basePrice,
                'insurance_price' => $insurancePrice,
                'final_price' => $finalPrice,
                'updated_by' => auth()->id(),
            ]);

            DB::commit();
            return response()->json(['success' => true, 'message' => 'Pricing updated successfully']);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Error updating pricing: " . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Failed to update pricing', 'error' => $e->getMessage()], 500);
        }
    }
}
