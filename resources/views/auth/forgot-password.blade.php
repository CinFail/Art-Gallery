@extends('layouts.guest')
@section('title', 'Forgot Password')

@section('content')
<h5 class="mb-2" style="font-family:'Cormorant Garamond',serif;font-size:1.3rem;color:#2c2012;">
    Forgot Your Password?
</h5>
<p class="text-muted small mb-4">
    Enter your email and we'll send you a password reset link.
</p>

@if(session('status'))
    <div class="alert alert-success small">{{ session('status') }}</div>
@endif

<form method="POST" action="{{ route('password.email') }}">
    @csrf

    <div class="mb-4">
        <label class="form-label">Email Address</label>
        <input type="email" name="email" value="{{ old('email') }}"
               class="form-control @error('email') is-invalid @enderror"
               placeholder="you@example.com" required autofocus>
        @error('email')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <button type="submit" class="btn btn-auth">Send Reset Link</button>

    <div class="text-center mt-3">
        <a href="{{ route('login') }}" class="small">← Back to Login</a>
    </div>
</form>
@endsection
