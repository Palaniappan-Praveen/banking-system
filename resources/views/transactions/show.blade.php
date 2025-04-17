@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Account: {{ $account->account_number }}</h1>
        <div>
            <span class="badge badge-primary">{{ $account->currency }}</span>
            <span class="badge badge-{{ $account->is_active ? 'success' : 'danger' }}">
                {{ $account->is_active ? 'Active' : 'Inactive' }}
            </span>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-body">
            <h5 class="card-title">Account Details</h5>
            <p class="card-text">
                <strong>Name:</strong> {{ $account->first_name }} {{ $account->last_name }}<br>
                <strong>Balance:</strong> {{ number_format($account->balance, 2) }} {{ $account->currency }}<br>
                <strong>Date of Birth:</strong>{{ $account->date_of_birth ? $account->date_of_birth : 'N/A' }}<br>
                <strong>Address:</strong> {{ $account->address }}
            </p>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Transaction History</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Amount</th>
                            <th>Type</th>
                            <th>Description</th>
                            <th>Related Account</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($transactions as $transaction)
                            <tr>
                                <td>{{ $transaction->created_at->format('d/m/Y H:i') }}</td>
                                <td class="{{ $transaction->amount > 0 ? 'text-success' : 'text-danger' }}">
                                    {{ number_format($transaction->amount, 2) }} {{ $transaction->currency }}
                                    @if($transaction->conversion_rate && $transaction->currency !== $account->currency)
                                        <small class="text-muted">({{ number_format($transaction->amount * $transaction->conversion_rate, 2) }} {{ $account->currency }})</small>
                                    @endif
                                </td>
                                <td>{{ ucfirst($transaction->type) }}</td>
                                <td>{{ $transaction->description }}</td>
                                <td>
                                    @if($transaction->relatedAccount)
                                        {{ $transaction->relatedAccount->account_number }}
                                    @else
                                        N/A
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            {{ $transactions->links() }}
        </div>
    </div>
</div>
@endsection