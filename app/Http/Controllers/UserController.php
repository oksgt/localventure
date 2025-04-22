<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class UserController extends Controller
{

    public function index(){
        if(session('role_id') !== 3){
            return view('admin.users.index');
        } else {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

    }

    public function getUsers(Request $request)
    {
        $currentUser = Auth::user();

        try {
            // Super Admin: Get all user data
            if ($currentUser->role_id == 1) {
                $users = User::with('role')->select(['id', 'username', 'name', 'email', 'phone', 'profile_picture', 'role_id']);
            }
            // Admin: Get users where parent_id equals the current admin's id
            elseif ($currentUser->role_id == 2) {
                $users = User::with('role')->where('parent_id', $currentUser->id)->select(['id', 'username', 'name', 'email', 'phone', 'profile_picture', 'role_id']);
            }
            // Operator: Return empty collection for no access
            elseif ($currentUser->role_id == 3) {
                $users = collect(); // Empty collection
            }

            return DataTables::of($users)
                ->addIndexColumn() // Add row numbering
                ->addColumn('role_name', function ($user) {
                    return $user->role ? $user->role->name : 'N/A'; // Role name from roles table
                })
                ->addColumn('action', function ($user) {
                    return '<a href="user/edit/' . $user->id . '" class="btn btn-sm btn-primary">Edit</a>
                            <form method="POST" action="user/delete/' . $user->id . '" style="display:inline;">
                                ' . csrf_field() . '
                                <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                            </form>';
                })
                ->rawColumns(['action']) // Ensure HTML actions render properly
                ->make(true);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500); // Handle server-side errors
        }
    }


    public function showUsers(Request $request)
    {
        $currentUser = Auth::user(); // Get the currently authenticated user

        try {
            // If the current user is a Super Admin
            if ($currentUser->role_id == 1) {
                $users = User::with('role')
                    ->withTrashed() // Include soft-deleted records
                    ->get(); // Fetch all users
            }
            // If the current user is an Administrator
            elseif ($currentUser->role_id == 2) {
                $users = User::with('role')
                    ->where('parent_id', $currentUser->id) // Fetch only child users
                    ->withTrashed()
                    ->get();
            }
            // If the current user is an Operator
            elseif ($currentUser->role_id == 3) {
                return response()->json(['error' => 'Unauthorized'], 403); // Prevent fetching user list
            }
            // No valid role
            else {
                return response()->json(['error' => 'Invalid role'], 403);
            }

            return response()->json(['users' => $users], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500); // Catch errors
        }
    }

    public function register(Request $request)
    {
        $request->validate([
            'username' => 'required|unique:users,username|max:255',
            'email' => 'required|unique:users,email|email|max:255',
            'name' => 'required|max:255',
            'password' => 'required|min:8',
            'role_id' => 'required|in:1,2,3', // Super Admin, Admin, Operator roles
            'parent_id' => 'nullable|exists:users,id',
        ]);

        try {
            DB::beginTransaction();

            $currentUser = Auth::user();

            if ($currentUser->role_id == 1 || ($currentUser->role_id == 2 && $request->role_id == 3 && $request->parent_id == $currentUser->id)) {
                $newUser = User::create([
                    'username' => $request->username,
                    'email' => $request->email,
                    'name' => $request->name,
                    'password' => Hash::make($request->password),
                    'phone' => $request->phone,
                    'role_id' => $request->role_id,
                    'parent_id' => $request->parent_id,
                    'created_by' => $currentUser->id,
                ]);

                DB::commit();
                return response()->json(['message' => 'User created successfully', 'user' => $newUser], 201);
            }

            DB::rollBack();
            return response()->json(['error' => 'Unauthorized to create user'], 403);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function edit(Request $request, $id)
    {
        $request->validate([
            'username' => 'sometimes|unique:users,username|max:255',
            'email' => 'sometimes|unique:users,email|email|max:255',
            'name' => 'required|max:255',
            'phone' => 'nullable|max:255',
        ]);

        $currentUser = Auth::user();
        $user = User::findOrFail($id);

        if ($currentUser->role_id == 1 || ($currentUser->role_id == 2 && $user->parent_id == $currentUser->id && $user->role_id == 3)) {
            try {
                $user->update([
                    'username' => $request->username,
                    'email' => $request->email,
                    'name' => $request->name,
                    'phone' => $request->phone,
                    'updated_by' => $currentUser->id,
                ]);

                return response()->json(['message' => 'User updated successfully', 'user' => $user], 200);
            } catch (\Exception $e) {
                return response()->json(['error' => $e->getMessage()], 500);
            }
        }

        return response()->json(['error' => 'Unauthorized to edit user'], 403);
    }

    public function updatePassword(Request $request, $id)
    {
        $request->validate([
            'password' => 'required|min:8|confirmed',
        ]);

        $currentUser = Auth::user();
        $user = User::findOrFail($id);

        if ($currentUser->role_id == 1 || $user->id == $currentUser->id) { // Super Admin can update any password, others can update their own
            try {
                $user->update([
                    'password' => Hash::make($request->password),
                    'updated_by' => $currentUser->id,
                ]);

                return response()->json(['message' => 'Password updated successfully'], 200);
            } catch (\Exception $e) {
                return response()->json(['error' => $e->getMessage()], 500);
            }
        }

        return response()->json(['error' => 'Unauthorized'], 403);
    }

    public function softDelete($id)
    {
        $currentUser = Auth::user();
        $user = User::findOrFail($id);

        if ($currentUser->role_id == 1 || ($currentUser->role_id == 2 && $user->parent_id == $currentUser->id && $user->role_id == 3)) {
            try {
                $user->deleted_by = $currentUser->id;
                $user->save();
                $user->delete();

                return response()->json(['message' => 'User soft-deleted successfully'], 200);
            } catch (\Exception $e) {
                return response()->json(['error' => $e->getMessage()], 500);
            }
        }

        return response()->json(['error' => 'Unauthorized'], 403);
    }
}
