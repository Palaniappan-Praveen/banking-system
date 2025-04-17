@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header">
            Account Details #{{ $account->account_number }}
            <a href="{{ route('admin.accounts.index') }}" class="btn btn-sm btn-secondary float-end">
                Back to List
            </a>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <h5>Account Information</h5>
                    <p><strong>Account Number:</strong> {{ $account->account_number }}</p>
                    <p><strong>Balance:</strong> {{ number_format($account->balance, 2) }} {{ $account->currency }}</p>
                    <p><strong>Status:</strong> 
                        <span class="badge bg-{{ $account->is_active ? 'success' : 'danger' }}">
                            {{ $account->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </p>
                </div>
                <div class="col-md-6">
                    <h5>Account Holder</h5>
                    <p><strong>Name:</strong> {{ $account->user->name }}</p>
                    <p><strong>Email:</strong> {{ $account->user->email }}</p>
                    <p><strong>Phone:</strong> {{ $account->user->phone ?? 'N/A' }}</p>
                </div>
            </div>

            <hr>

            <h5 class="mt-4">Transactions</h5>
            @if($account->transactions->count())
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Amount</th>
                            <th>Type</th>
                            <th>Description</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($account->transactions as $transaction)
                        <tr>
                            <td>{{ $transaction->created_at->format('d/m/Y H:i') }}</td>
                            <td class="{{ $transaction->amount > 0 ? 'text-success' : 'text-danger' }}">
                                {{ number_format($transaction->amount, 2) }} {{ $transaction->currency }}
                            </td>
                            <td>{{ ucfirst($transaction->type) }}</td>
                            <td>{{ $transaction->description ?? 'N/A' }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="alert alert-info">No transactions found</div>
            @endif
        </div>
    </div>
</div>
@endsection