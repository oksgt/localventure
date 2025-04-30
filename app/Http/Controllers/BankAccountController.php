<?php

namespace App\Http\Controllers;

use App\Models\BankAccount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BankAccountController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.bank-accounts.index');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'bank_name' => 'required|max:255',
            'account_name' => 'required|max:255',
            'account_number' => 'required|max:50|unique:bank_accounts,account_number,',
            'is_public' => 'required|boolean',
        ], [
            'bank_name.required' => 'The bank name is required.',
            'bank_name.max' => 'Bank name cannot exceed 255 characters.',

            'account_name.required' => 'The account name is required.',
            'account_name.max' => 'Account name cannot exceed 255 characters.',

            'account_number.required' => 'The account number is required.',
            'account_number.max' => 'Account number cannot exceed 50 characters.',
            'account_number.unique' => 'This account number is already in use. Please use a different one.',

            'is_public.required' => 'Visibility setting is required.',
            'is_public.boolean' => 'Visibility must be either public (1) or private (0).',
        ]);

        $bankAccount = BankAccount::create(array_merge(
            $request->all(),
            ['created_by' => Auth::id()]
        ));

        return response()->json(['success' => true, 'data' => $bankAccount, 'message' => 'Bank account created successfully!']);
    }

    public function getData()
    {
        return datatables()->of(BankAccount::query())
            ->addColumn('action', function ($account) {
                return '<button class="btn btn-sm btn-warning edit-account" data-id="' . $account->id . '"><i class="fa fa-edit"></i>Edit</button>
                        <button class="btn btn-sm btn-danger delete-account" data-id="' . $account->id . '"><i class="fa fa-trash"></i>Delete</button>';
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $bankAccount = BankAccount::findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $bankAccount
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'bank_name' => 'required|max:255',
            'account_name' => 'required|max:255',
            'account_number' => 'required|max:50|unique:bank_accounts,account_number,' . $id,
            'is_public' => 'required|boolean',
        ], [
            'bank_name.required' => 'The bank name is required.',
            'bank_name.max' => 'Bank name cannot exceed 255 characters.',

            'account_name.required' => 'The account name is required.',
            'account_name.max' => 'Account name cannot exceed 255 characters.',

            'account_number.required' => 'The account number is required.',
            'account_number.max' => 'Account number cannot exceed 50 characters.',
            'account_number.unique' => 'This account number is already in use. Please use a different one.',

            'is_public.required' => 'Visibility setting is required.',
            'is_public.boolean' => 'Visibility must be either public (1) or private (0).',
        ]);

        $bankAccount = BankAccount::findOrFail($id);
        $bankAccount->update(array_merge(
            $request->all(),
            ['updated_by' => Auth::id()]
        ));

        return response()->json(['success' => true, 'message' => 'Bank account updated successfully!']);
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $bankAccount = BankAccount::findOrFail($id);
        $bankAccount->update(['deleted_by' => Auth::id()]);
        $bankAccount->delete();

        return response()->json(['success' => true, 'message' => 'Bank account deleted successfully!']);
    }
}
