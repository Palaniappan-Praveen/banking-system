@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">Verify OTP</div>
            {{ session('otp') }}
            <div class="card-body">
                @if (session('success'))
                    <div class="alert alert-success" role="alert">
                        {{ session('success') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('otp.verify') }}">
                    @csrf

                    <div class="form-group">
                        <label for="otp">Enter OTP</label>
                        <input id="otp" type="number" class="form-control @error('otp') is-invalid @enderror" name="otp" required>
                        @error('otp')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class="form-group mb-0">
                        <button type="submit" class="btn btn-primary">
                            Verify OTP
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection