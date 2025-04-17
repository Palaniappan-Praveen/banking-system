@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header">
            Edit Account #{{ $account->account_number }}
            <a href="{{ route('admin.accounts.show', $account->id) }}" class="btn btn-sm btn-secondary float-end">
                Back to Account
            </a>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('admin.accounts.update', $account->id) }}">
                @csrf
                @method('PUT')

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="first_name" class="form-label">First Name</label>
                        <input type="text" class="form-control" id="first_name" name="first_name" 
                               value="{{ old('first_name', $account->first_name) }}" required>
                    </div>
                    <div class="col-md-6">
                        <label for="last_name" class="form-label">Last Name</label>
                        <input type="text" class="form-control" id="last_name" name="last_name" 
                               value="{{ old('last_name', $account->last_name) }}" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="currency" class="form-label">Currency</label>
                    <select class="form-select" id="currency" name="currency" required>
                        <option value="USD" {{ $account->currency == 'USD' ? 'selected' : '' }}>USD</option>
                        <option value="EUR" {{ $account->currency == 'EUR' ? 'selected' : '' }}>EUR</option>
                        <option value="GBP" {{ $account->currency == 'GBP' ? 'selected' : '' }}>GBP</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="balance" class="form-label">Balance</label>
                    <input type="number" step="0.01" class="form-control" id="balance" name="balance" 
                           value="{{ old('balance', $account->balance) }}" required>
                </div>

               <div class="mb-3 form-check">
    <input type="hidden" name="is_active" value="0"> <!-- Default value when unchecked -->
    <input type="checkbox" class="form-check-input" id="is_active" name="is_active" value="1"
           {{ $account->is_active ? 'checked' : '' }}>
    <label class="form-check-label" for="is_active">Active Account</label>
</div>

                <button type="submit" class="btn btn-primary">Update Account</button>
            </form>
        </div>
    </div>
</div>
@endsection