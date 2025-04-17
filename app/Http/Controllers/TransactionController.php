<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Transaction;
use App\Models\CurrencyRate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class TransactionController extends Controller
{
    public function index()
    {
        $accounts = auth()->user()->accounts;
        return view('transactions.index', compact('accounts'));
    }

    public function show(Account $account)
    {
        $transactions = $account->transactions()
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('transactions.show', compact('account', 'transactions'));
    }

    public function createTransfer(Account $account)
    {
        $accounts = Account::where('id', '!=', $account->id)->get();
        return view('transactions.transfer', compact('account', 'accounts'));
    }

    public function storeTransfer(Request $request, Account $account)
    {
        $request->validate([
            'recipient_account' => 'required|exists:accounts,account_number',
            'amount' => 'required|numeric|min:0.01|max:' . $account->balance,
            'currency' => 'required|in:USD,GBP,EUR',
            'description' => 'nullable|string',
        ]);

        $recipient = Account::where('account_number', $request->recipient_account)->first();

        // Get conversion rate
        $conversionRate = $this->getConversionRate($account->currency, $request->currency);

        // Calculate amount with spread (0.01%)
        $amountWithSpread = $request->amount * $conversionRate * 0.9999;

        // Check if sender has sufficient balance
        if ($account->balance < $request->amount) {
            return back()->with('error', 'Insufficient balance!');
        }

        // Start transaction
        \DB::transaction(function () use ($account, $recipient, $request, $amountWithSpread, $conversionRate) {
            // Deduct from sender
            $account->decrement('balance', $request->amount);

            // Add to recipient
            $recipient->increment('balance', $amountWithSpread);

            // Create transactions
            Transaction::create([
                'account_id' => $account->id,
                'amount' => -$request->amount,
                'type' => 'transfer',
                'currency' => $account->currency,
                'conversion_rate' => $conversionRate,
                'description' => $request->description,
                'related_account_id' => $recipient->id,
            ]);

            Transaction::create([
                'account_id' => $recipient->id,
                'amount' => $amountWithSpread,
                'type' => 'transfer',
                'currency' => $recipient->currency,
                'conversion_rate' => $conversionRate,
                'description' => $request->description,
                'related_account_id' => $account->id,
            ]);
        });

        return redirect()->route('transactions.show', $account)
            ->with('success', 'Transfer completed successfully!');
    }

    private function getConversionRate($from, $to)
    {
        if ($from === $to) {
            return 1;
        }

        // Check if we have a recent rate in database
        $rate = CurrencyRate::where('from_currency', $from)
            ->where('to_currency', $to)
            ->where('created_at', '>', now()->subHours(24))
            ->latest()
            ->first();

        if ($rate) {
            return $rate->rate;
        }

        // Fetch from API
        $response = Http::get('https://api.exchangerate-api.com/v4/latest/' . $from);
        
        if ($response->successful()) {
            $data = $response->json();
            $rate = $data['rates'][$to] ?? 1;

            // Store in database
            CurrencyRate::create([
                'from_currency' => $from,
                'to_currency' => $to,
                'rate' => $rate,
            ]);

            return $rate;
        }

        return 1; // Fallback
    }
}
