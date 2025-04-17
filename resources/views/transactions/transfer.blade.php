@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Transfer Money from {{ $account->account_number }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('transactions.transfer.store', $account) }}">
                        @csrf

                        <div class="form-group">
                            <label for="recipient_account">Recipient Account Number</label>
                            <select name="recipient_account" id="recipient_account" class="form-control" required>
                                <option value="">Select Account</option>
                                @foreach($accounts as $acc)
                                    <option value="{{ $acc->account_number }}">{{ $acc->account_number }} ({{ $acc->first_name }} {{ $acc->last_name }})</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="amount">Amount</label>
                            <div class="input-group">
                                <input type="number" step="0.01" min="0.01" max="{{ $account->balance }}" name="amount" id="amount" class="form-control" required>
                                <div class="input-group-append">
                                    <span class="input-group-text">{{ $account->currency }}</span>
                                </div>
                            </div>
                            <small class="form-text text-muted">Current balance: {{ number_format($account->balance, 2) }} {{ $account->currency }}</small>
                        </div>

                        <div class="form-group">
                            <label for="currency">Transfer Currency</label>
                            <select name="currency" id="currency" class="form-control" required>
                                <option value="USD">USD</option>
                                <option value="GBP">GBP</option>
                                <option value="EUR">EUR</option>
                            </select>
                            <small class="form-text text-muted">A 0.01% spread will be applied for currency conversion</small>
                        </div>

                        <div class="form-group">
                            <label for="description">Description</label>
                            <textarea name="description" id="description" class="form-control" rows="2"></textarea>
                        </div>

                        <div class="form-group mb-0">
                            <button type="submit" class="btn btn-primary">
                                Transfer Money
                            </button>
                            <a href="{{ route('transactions.show', $account) }}" class="btn btn-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection