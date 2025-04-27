<?php

namespace App\Http\Controllers;

use App\Models\Destination;
use Illuminate\Http\Request;
use App\Models\Wisata;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;

class DestinationController extends Controller
{
    // Display a listing of the resource.
    public function index()
    {
        return view('admin.destination.index');
    }

    public function getDestinations(Request $request)
    {
        try {
            DB::beginTransaction(); // Start transaction

            $destinations = Destination::query()->select(['id', 'name', 'description', 'latlon']);

            $dataTable = DataTables::of($destinations)
                ->addIndexColumn()
                ->editColumn('description', function ($destination) {
                    return strlen($destination->description) > 30
                        ? substr($destination->description, 0, 30) . '...'
                        : $destination->description;
                })
                ->addColumn('gallery', function ($destination) {
                    return '<a href="#" class="btn btn-sm btn-info"><i class="ti-gallery"></i></a>'; // Set URL dynamically later
                })
                ->addColumn('location', function ($destination) {
                    return '<a href="#" class="btn btn-sm btn-success"><i class="ti-location-pin"></i></a>'; // Set URL dynamically later
                })
                ->addColumn('action', function ($destination) {
                    return '<button class="btn btn-sm btn-primary edit-destination" data-id="' . $destination->id . '">Edit</button>
                            <button class="btn btn-sm btn-danger delete-destination" data-id="' . $destination->id . '">Delete</button>';
                })
                ->rawColumns(['gallery', 'location', 'action'])
                ->make(true);

            DB::commit(); // Commit transaction

            return $dataTable;
        } catch (\Exception $e) {
            DB::rollBack(); // Rollback transaction in case of failure
            Log::error("Error loading destinations: " . $e->getMessage());

            return response()->json(['success' => false, 'message' => 'Failed to load destinations', 'error' => $e->getMessage()], 500);
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'        => 'required|max:255',
            'description' => 'required',
            'address'     => 'nullable|max:255',
            'latlon'      => 'required|max:255',
            'available'   => 'required|boolean',
        ]);

        try {
            DB::beginTransaction(); // Start transaction

            $destination = Destination::create([
                'name'        => $request->name,
                'description' => $request->description,
                'address'     => $request->address,
                'latlon'      => $request->latlon,
                'available'   => $request->available,
                'created_by'  => Auth::id(), // Get the logged-in user ID
            ]);

            DB::commit(); // Commit transaction

            return response()->json(['success' => true, 'message' => 'Destination added successfully'], 200);
        } catch (\Exception $e) {
            DB::rollBack(); // Rollback transaction in case of failure
            Log::error("Error inserting destination: " . $e->getMessage());

            return response()->json(['success' => false, 'message' => 'Failed to add destination', 'error' => $e->getMessage()], 500);
        }
    }

    public function edit($id)
    {
        try {
            $destination = Destination::findOrFail($id);
            return response()->json(['success' => true, 'data' => $destination], 200);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to fetch destination data', 'error' => $e->getMessage()], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name'        => 'required|max:255',
            'description' => 'required',
            'address'     => 'nullable|max:255',
            'latlon'      => 'required|max:255',
            'available'   => 'required|boolean',
        ]);

        try {
            DB::beginTransaction(); // Start transaction

            $destination = Destination::findOrFail($id);
            $destination->update([
                'name'        => $request->name,
                'description' => $request->description,
                'address'     => $request->address,
                'latlon'      => $request->latlon,
                'available'   => $request->available,
                'updated_by'  => Auth::id(),
            ]);

            DB::commit(); // Commit transaction

            return response()->json(['success' => true, 'message' => 'Destination updated successfully'], 200);
        } catch (\Exception $e) {
            DB::rollBack(); // Rollback transaction
            Log::error("Error updating destination: " . $e->getMessage());

            return response()->json(['success' => false, 'message' => 'Failed to update destination', 'error' => $e->getMessage()], 500);
        }
    }


}

