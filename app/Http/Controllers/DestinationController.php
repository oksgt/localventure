<?php

namespace App\Http\Controllers;

use App\Models\Destination;
use Illuminate\Http\Request;
use App\Models\Wisata;
use Illuminate\Support\Facades\DB;
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
            \Log::error("Error loading destinations: " . $e->getMessage());

            return response()->json(['success' => false, 'message' => 'Failed to load destinations', 'error' => $e->getMessage()], 500);
        }
    }

}

