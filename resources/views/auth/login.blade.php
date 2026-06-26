@extends('layouts.guest')
@section('title', 'Login')

@section('content')
<h5 class="mb-4" style="font-family:'Cormorant Garamond',serif;font-size:1.3rem;color:#2c2012;">
    Sign In to Your Account
</h5>

@if(session('status'))
    <div class="alert alert-success small">{{ session('status') }}</div>
@endif

<form method="POST" action="{{ route('login') }}">
    @csrf

    <div class="mb-3">
        <label class="form-label">Email Address</label>
        <input type="email" name="email" value="{{ old('email') }}"
               class="form-control @error('email') is-invalid @enderror"
               placeholder="you@example.com" autofocus required>
        @error('email')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-3">
        <label class="form-label">Password</label>
        <input type="password" name="password"
               class="form-control @error('password') is-invalid @enderror"
               required>
        @error('password')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div class="form-check">
            <input class="form-check-input" type="checkbox" name="remember" id="remember">
            <label class="form-check-label small" for="remember">Remember me</label>
        </div>
        <a href="{{ route('password.request') }}" class="small">Forgot Password?</a>
    </div>

    <button type="submit" class="btn btn-auth">Sign In</button>
</form>
@endsection
