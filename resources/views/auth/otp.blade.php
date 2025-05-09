@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">Verify Phone Number For Registration</div>

            <div class="card-body">
                <form method="POST" action="{{ route('otp.send') }}">
                    @csrf

                    <div class="form-group">
                        <label for="phone">Phone Number</label>
                        <input id="phone" type="tel" class="form-control @error('phone') is-invalid @enderror" name="phone" required>
                        @error('phone')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class="form-group mb-0">
                        <button type="submit" class="btn btn-primary">
                            Send OTP
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection