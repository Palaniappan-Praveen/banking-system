@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">My Accounts</h1>

    <div class="row">
        @foreach($accounts as $account)
            <div class="col-md-4 mb-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">{{ $account->account_number }}</h5>
                        <p class="card-text">
                            Balance: {{ number_format($account->balance, 2) }} {{ $account->currency }}
                        </p>
                        <a href="{{ route('transactions.show', $account) }}" class="btn btn-primary">View Transactions</a>
                        <a href="{{ route('transactions.transfer', $account) }}" class="btn btn-success">Transfer Money</a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection