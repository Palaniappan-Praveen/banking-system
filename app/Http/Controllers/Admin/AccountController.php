<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\User;
use Illuminate\Http\Request;

class AccountController extends Controller
{
    public function index()
    {
        $accounts = Account::with('user')->paginate(10);
        return view('admin.accounts.index', compact('accounts'));
    }

    public function create()
    {
        $users = User::where('role', 'user')->get();
        return view('admin.accounts.create', compact('users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'date_of_birth' => 'required|date',
            'address' => 'required|string',
            'currency' => 'required|in:USD,GBP,EUR',
        ]);

        Account::create([
            'user_id' => $request->user_id,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'date_of_birth' => $request->date_of_birth,
            'address' => $request->address,
            'currency' => $request->currency,
            'balance' => 10000, // Initial balance
        ]);

        return redirect()->route('admin.accounts.index')->with('success', 'Account created successfully!');
    }

    // public function show(Account $account)
    // {
    //     $transactions = $account->transactions()->latest()->paginate(10);
    //     return view('admin.accounts.show', compact('account', 'transactions'));
    // }

    public function edit(Account $account)
    {
        return view('admin.accounts.edit', compact('account'));
    }

    public function update(Request $request, Account $account)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'date_of_birth' => 'date',
            'address' => 'string',
            'is_active' => 'sometimes|boolean',
        ]);
        $validated['is_active'] = $request->has('is_active');
        $account->update($validated);

        return redirect()->route('admin.accounts.index')->with('success', 'Account updated successfully!');
    }

    public function destroy(Account $account)
    {
        $account->delete();
        return redirect()->route('admin.accounts.index')->with('success', 'Account deleted successfully!');
    }

    public function search(Request $request)
{
    $request->validate(['search' => 'nullable|string|max:255']);

    $accounts = Account::query()
        ->when($request->search, function ($query) use ($request) {
            $query->where(function ($q) use ($request) {
                $q->where('account_number', 'like', "%{$request->search}%")
                  ->orWhere('first_name', 'like', "%{$request->search}%")
                  ->orWhereHas('user', fn($q) => 
                      $q->where('name', 'like', "%{$request->search}%")
                  );
            });
        })
        ->paginate(10);

    return view('admin.accounts.index', [
        'accounts' => $accounts,
        'searchTerm' => $request->search
    ]);
}
    public function show(Account $account)
{
    // Eager load relationships
    $account->load(['user', 'transactions']);
    
    return view('admin.accounts.show', compact('account'));
}

}