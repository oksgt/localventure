<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\UserMapping;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class UserMappingController extends Controller
{
    public function index()
    {
        return view('admin.mapping-user.index'); // Load the Blade view only
    }

    public function getData()
    {
        $mappings = UserMapping::select(
                'user_mapping.id',
                'user_mapping.user_id', // Ensure user_id is selected
                'user_mapping.destination_id', // Ensure destination_id is selected
                'users.name',
                'destinations.name as destination_name'
            )
            ->join('users', 'users.id', '=', 'user_mapping.user_id')
            ->join('destinations', 'destinations.id', '=', 'user_mapping.destination_id');

        return datatables()->of($mappings)
            ->addColumn('action', function ($mapping) {
                return '<button class="btn btn-sm btn-primary edit-mapping"
                            data-id="' . $mapping->id . '"
                            data-user="' . $mapping->user_id . '"
                            data-destination="' . $mapping->destination_id . '">
                            Edit
                        </button>
                        <button class="btn btn-sm btn-danger delete-mapping" data-id="' . $mapping->id . '">
                            Delete
                        </button>';
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'destination_id' => 'required|exists:destinations,id',
        ]);

        try {
            DB::beginTransaction();

            // Check if the mapping already exists
            $existingMapping = UserMapping::where('user_id', $request->user_id)
                ->where('destination_id', $request->destination_id)
                ->first();

            if ($existingMapping) {
                return response()->json(['success' => true, 'message' => 'User is already mapped to this destination']);
            }

            // Get role_id from the users table
            $userRole = User::where('id', $request->user_id)->value('role_id');

            if (!$userRole) {
                return response()->json(['success' => false, 'message' => 'User role not found'], 404);
            }

            // Create mapping
            $mapping = UserMapping::create([
                'user_id' => $request->user_id,
                'destination_id' => $request->destination_id,
                'role_id' => $userRole,
            ]);

            DB::commit();

            return response()->json(['success' => true, 'message' => 'User mapping saved successfully', 'mapping_id' => $mapping->id]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Error saving user mapping: " . $e->getMessage());

            return response()->json(['success' => false, 'message' => 'Failed to save mapping', 'error' => $e->getMessage()], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'destination_id' => 'required|exists:destinations,id',
        ]);

        try {
            DB::beginTransaction();

            // Find mapping by ID
            $mapping = UserMapping::findOrFail($id);

            // Get updated role_id from users table
            $userRole = User::where('id', $request->user_id)->value('role_id');

            if (!$userRole) {
                return response()->json(['success' => false, 'message' => 'User role not found'], 404);
            }

            // Update mapping
            $mapping->update([
                'user_id' => $request->user_id,
                'destination_id' => $request->destination_id,
                'role_id' => $userRole,
            ]);

            DB::commit();

            return response()->json(['success' => true, 'message' => 'User mapping updated successfully']);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Error updating user mapping: " . $e->getMessage());

            return response()->json(['success' => false, 'message' => 'Failed to update mapping', 'error' => $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        try {
            DB::beginTransaction();

            $mapping = UserMapping::findOrFail($id); // Find mapping
            $mapping->delete(); // Delete it

            DB::commit();

            return response()->json(['success' => true, 'message' => 'User mapping deleted successfully']);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Error deleting user mapping: " . $e->getMessage());

            return response()->json(['success' => false, 'message' => 'Failed to delete mapping', 'error' => $e->getMessage()], 500);
        }
    }
}
