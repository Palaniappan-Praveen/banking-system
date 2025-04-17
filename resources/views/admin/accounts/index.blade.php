@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Manage Accounts</h1>
        <a href="{{ route('admin.accounts.create') }}" class="btn btn-primary">Create New Account</a>
    </div>

    <div class="card mb-4">
        <div class="card-body">
           <form action="{{ route('admin.accounts.search') }}" method="GET" class="mb-4">
    <div class="input-group">
        <input type="text" 
               name="search" 
               class="form-control" 
               placeholder="Search by name, account #, or email"
               value="{{ request('search') }}">
        <button type="submit" class="btn btn-primary">
            <i class="fas fa-search"></i> Search
        </button>
        @if(request()->has('search'))
            <a href="{{ route('admin.accounts.index') }}" class="btn btn-outline-secondary">
                Clear
            </a>
        @endif
    </div>
</form>
            @if(request()->has('search'))
         <div class="mt-2">
        <a href="{{ route('admin.accounts.index') }}" class="btn btn-sm btn-secondary">
            Clear Search
        </a>
        </div>
    @endif
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Account Number</th>
                            <th>Account Holder</th>
                            <th>Balance</th>
                            <th>Currency</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($accounts as $account)
                            <tr>
                                <td>{{ $account->account_number }}</td>
                                <td>{{ $account->first_name }} {{ $account->last_name }}</td>
                                <td>{{ number_format($account->balance, 2) }}</td>
                                <td>{{ $account->currency }}</td>
                                <td>
                                    <span class="badge badge-{{ $account->is_active ? 'success' : 'danger' }}">
                                        {{ $account->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ route('admin.accounts.show', $account) }}" class="btn btn-sm btn-info">View</a>
                                    <a href="{{ route('admin.accounts.edit', $account) }}" class="btn btn-sm btn-primary">Edit</a>
                                    <form action="{{ route('admin.accounts.destroy', $account) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            {{ $accounts->links() }}
        </div>
    </div>
</div>
@endsection