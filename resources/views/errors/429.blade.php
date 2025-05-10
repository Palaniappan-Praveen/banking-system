@extends('layouts.app')

@section('content')
<div class="container">

<h2>Oops! You're going too fast!</h2>
    <p>{{ $message }}</p>
    <p>Please wait for a minute before trying again.Back to <a href="{{ route('login') }}">Login</a> page.
    </p>




</div>
@endsection	