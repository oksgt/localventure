<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Str;

class UserController extends Controller
{

    public function index()
    {
        if (session('role_id') !== 3) {
            return view('admin.users.index');
        } else {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
    }

    public function addUser(Request $request)
    {
        return view('admin.users.add');
    }

    public function getRoles()
    {
        try {
            $roles = Role::select('id', 'name')
                ->where('id', '!=', 1) // Exclude superadmin (assuming superadmin has id = 1)
                ->get();

            return response()->json($roles, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to load roles'], 500);
        }
    }

    public function getAdmins()
    {
        try {
            $admins = User::where('role_id', 2)->select('id', 'name')->get();
            return response()->json($admins, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to load admins'], 500);
        }
    }

    public function edit($id)
    {
        try {
            $user = User::with('role')->findOrFail($id); // Find user or return 404

            return response()->json([
                'success' => true,
                'data' => $user
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'User not found',
                'error' => $e->getMessage()
            ], 404);
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'role_id'           => 'required|exists:roles,id',
            'username'          => 'required|unique:users,username|max:255',
            'name'              => 'required|max:255',
            'email'             => 'required|unique:users,email|email|max:255',
            'phone'             => 'nullable|max:255',
            'password'          => [
                'required',
                'min:8',
                'confirmed',
                'regex:/^[a-zA-Z0-9]+$/', // Ensure only alphanumeric characters
            ],
            'password_confirmation' => 'required|same:password',
            'parent_list'           => $request->role_id == 3 ? 'required|exists:users,id' : 'nullable',
        ], [
            'password.regex'    => 'Password must contain only letters and numbers (no special characters).',
            'parent_list.required' => 'Parent selection is required for Operator role.',
        ]);

        try {
            DB::beginTransaction(); // Start transaction

            $newUser = User::create([
                'role_id'   => $request->role_id,
                'username'  => $request->username,
                'name'      => $request->name,
                'email'     => $request->email,
                'phone'     => $request->phone,
                'password'  => Hash::make($request->password),
                'parent_id' => $request->role_id == 3 ? $request->parent_list : null,
                'created_by' => Auth::user()->id,
                'remember_token' => Str::random(10),
            ]);

            DB::commit(); // Commit transaction

            return response()->json(['success' => true, 'message' => 'User created successfully!'], 201);
        } catch (\Exception $e) {
            DB::rollBack(); // Rollback transaction on error
            Log::error("User creation failed: " . $e->getMessage()); // Log error for debugging
            return response()->json(['success' => false, 'message' => 'Failed to create user', 'error' => $e->getMessage()], 500);
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
            ->addIndexColumn()
            ->addColumn('role_name', function ($user) {
                return $user->role ? $user->role->name : 'N/A';
            })
            ->addColumn('action', function ($user) {
                $currentUserId = Auth::id(); // Get logged-in user ID

                // Hide Edit and Delete buttons for current user
                if ($user->id == $currentUserId) {
                    return '<span class="text-muted">No actions available</span>'; // Placeholder for UI consistency
                }

                return '<button type="button" class="btn btn-sm btn-primary" onclick="editUser(' . $user->id . ')">Edit</button>
                        <button type="button" class="btn btn-sm btn-danger" onclick="deleteUser(' . $user->id . ')">Delete</button>';
            })
            ->rawColumns(['action'])
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

    public function softDelete($id)
    {
        try {
            $currentUser = Auth::user();
            $user = User::findOrFail($id);

            // Check if the logged-in user has permission to delete
            if ($currentUser->role_id == 1 || ($currentUser->role_id == 2 && $user->parent_id == $currentUser->id && $user->role_id == 3)) {
                DB::beginTransaction(); // Start transaction for reliability

                $user->update(['deleted_by' => $currentUser->id]); // Record who deleted the user
                $user->delete(); // Soft delete

                DB::commit();
                return response()->json(['success' => true, 'message' => 'User deleted successfully'], 200);
            }

            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        } catch (\Exception $e) {
            DB::rollBack(); // Roll back if an error occurs
            Log::error("Soft delete failed: " . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Failed to delete user', 'error' => $e->getMessage()], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id); // Fetch current user data

        $request->validate([
            'role_id'    => 'required|exists:roles,id',
            'username'   => $request->username !== $user->username ? 'required|max:255|unique:users,username,' . $id : 'required|max:255',
            'name'       => 'required|max:255',
            'email'      => $request->email !== $user->email ? 'required|email|max:255|unique:users,email,' . $id : 'required|email|max:255',
            'phone'      => 'nullable|max:255',
            'parent_list'=> $request->role_id == 3 ? 'required|exists:users,id' : 'nullable',
        ]);

        try {
            DB::beginTransaction();

            $user->update([
                'role_id'   => $request->role_id,
                'username'  => $request->username,
                'name'      => $request->name,
                'email'     => $request->email,
                'phone'     => $request->phone,
                'parent_id' => $request->role_id == 3 ? $request->parent_list : null,
            ]);

            DB::commit();

            return response()->json(['success' => true, 'message' => 'User updated successfully'], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Failed to update user', 'error' => $e->getMessage()], 500);
        }
    }

    public function getProfile()
    {
        try {
            $user = Auth::user(); // Get current user

            return response()->json([
                'success' => true,
                'data' => $user
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve profile data',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user(); // Get current user

        $request->validate([
            'username_profile' => $request->username_profile !== $user->username ? 'required|max:255|unique:users,username,' . $user->id : 'required|max:255',
            'name_profile'     => 'required|max:255',
            'email_profile'    => $request->email_profile !== $user->email ? 'required|email|max:255|unique:users,email,' . $user->id : 'required|email|max:255',
            'phone_profile'    => 'nullable|max:255',
        ]);

        try {
            $user->update([
                'username' => $request->username_profile,
                'name'     => $request->name_profile,
                'email'    => $request->email_profile,
                'phone'    => $request->phone_profile,
                'updated_by' => $user->id,
                'updated_at' => now()
            ]);

            return response()->json(['success' => true, 'message' => 'Profile updated successfully'], 200);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to update profile', 'error' => $e->getMessage()], 500);
        }
    }


    public function updatePassword(Request $request)
    {
        $user = Auth::user(); // Get current user

        $request->validate([
            'old_password'        => 'required',
            'new_password'        => 'required|min:8|regex:/^[a-zA-Z0-9]+$/|confirmed',
            'new_password_confirmation' => 'required|same:new_password',
        ], [
            'new_password.regex'  => 'Password must contain only letters and numbers (no special characters).',
        ]);

        // Check if old password matches
        if (!Hash::check($request->old_password, $user->password)) {
            return response()->json(['success' => false, 'message' => 'Old password is incorrect'], 400);
        }

        try {
            $user->update([
                'password' => Hash::make($request->new_password),
            ]);

            Auth::logout(); // Log out the user
            Session::flash('success', 'Password updated successfully. Please log in again.');

            return response()->json(['success' => true, 'redirect' => route('login')], 200);

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to update password', 'error' => $e->getMessage()], 500);
        }
    }

    public function list()
    {
        $users = User::select('users.id', 'users.name', 'roles.name as role')
            ->join('roles', 'users.role_id', '=', 'roles.id')
            ->where('users.role_id', '!=', Auth::user()->role_id)
            ->where('users.deleted_at', null) // Exclude soft-deleted users
            ->get();

        return response()->json(['success' => true, 'data' => $users]);
    }
}
