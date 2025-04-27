<?php

namespace App\Http\Controllers;

use App\Models\Destination;
use App\Models\DestinationGallery;
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
                    return '<a href="#" class="btn btn-sm btn-info upload-gallery-btn" data-id="' . $destination->id . '">
                                <i class="ti-gallery"></i>
                            </a>';
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


    public function destroy($id)
    {
        try {
            DB::beginTransaction(); // Start transaction

            $destination = Destination::findOrFail($id);
            $destination->update([
                'deleted_by' => Auth::id(), // Track who deleted the record
            ]);
            $destination->delete(); // Soft delete

            DB::commit(); // Commit transaction

            return response()->json(['success' => true, 'message' => 'Destination deleted successfully'], 200);
        } catch (\Exception $e) {
            DB::rollBack(); // Rollback transaction in case of failure
            Log::error("Error deleting destination: " . $e->getMessage());

            return response()->json(['success' => false, 'message' => 'Failed to delete destination', 'error' => $e->getMessage()], 500);
        }
    }

    public function fetchGallery($destinationId)
    {
        try {
            $gallery = DestinationGallery::where('destination_id', $destinationId)->first();

            if ($gallery) {
                return response()->json([
                    'success' => true,
                    'image_url' => asset('storage/destination/' . $gallery->filename),
                    'gallery_id' => $gallery->id
                ], 200);
            }

            return response()->json(['success' => false, 'message' => 'No image found'], 404);
        } catch (\Exception $e) {
            Log::error("Error fetching gallery: " . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Failed to fetch image', 'error' => $e->getMessage()], 500);
        }
    }

    public function upload(Request $request)
    {
        $request->validate([
            'destination_id' => 'required|exists:destinations,id',
            'image' => 'required|mimes:jpg,jpeg,png|max:5120', // Allow only JPG/PNG, max size 5MB
        ]);

        try {
            DB::beginTransaction();

            $file = $request->file('image');
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $filePath = 'storage/destination/' . $filename;

            // Check if a gallery record already exists for this destination
            $existingGallery = DestinationGallery::where('destination_id', $request->destination_id)->first();

            if ($existingGallery) {
                // Delete existing file
                $oldFilePath = storage_path('app/public/destination/' . $existingGallery->filename);
                if (file_exists($oldFilePath)) {
                    unlink($oldFilePath);
                }

                // Update existing record
                $existingGallery->update([
                    'original_file_name' => $file->getClientOriginalName(),
                    'filename' => $filename,
                    'file_ext' => $file->getClientOriginalExtension(),
                    'file_size' => $file->getSize(),
                    'updated_by' => Auth::id(),
                ]);
            } else {
                // Store new record
                DestinationGallery::create([
                    'destination_id' => $request->destination_id,
                    'original_file_name' => $file->getClientOriginalName(),
                    'filename' => $filename,
                    'file_ext' => $file->getClientOriginalExtension(),
                    'file_size' => $file->getSize(),
                    'created_by' => Auth::id(),
                ]);
            }

            // Store file in the correct directory
            $file->storeAs('destination', $filename, 'public');

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Image uploaded successfully',
                'image_url' => asset('storage/destination/' . $filename)
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Error uploading image: " . $e->getMessage());

            return response()->json(['success' => false, 'message' => 'Failed to upload image', 'error' => $e->getMessage()], 500);
        }
    }

    public function remove($id)
    {
        try {
            DB::beginTransaction();

            $gallery = DestinationGallery::findOrFail($id);
            $filePath = storage_path('app/public/destination/' . $gallery->filename);

            // Delete file
            if (file_exists($filePath)) {
                unlink($filePath);
            }

            // Delete record
            $gallery->delete();

            DB::commit();

            return response()->json(['success' => true, 'message' => 'Image removed successfully'], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Error removing image: " . $e->getMessage());

            return response()->json(['success' => false, 'message' => 'Failed to remove image', 'error' => $e->getMessage()], 500);
        }
    }

}

