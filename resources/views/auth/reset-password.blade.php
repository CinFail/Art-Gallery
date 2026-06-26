@extends('layouts.guest')
@section('title', 'Reset Password')

@section('content')
<h5 class="mb-4" style="font-family:'Cormorant Garamond',serif;font-size:1.3rem;color:#2c2012;">
    Reset Your Password
</h5>

<form method="POST" action="{{ route('password.update') }}">
    @csrf
    <input type="hidden" name="token" value="{{ $token }}">

    <div class="mb-3">
        <label class="form-label">Email Address</label>
        <input type="email" name="email" value="{{ old('email', $email) }}"
               class="form-control @error('email') is-invalid @enderror" required>
        @error('email')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-3">
        <label class="form-label">New Password</label>
        <input type="password" name="password"
               class="form-control @error('password') is-invalid @enderror"
               placeholder="Minimum 8 characters" required>
        @error('password')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-4">
        <label class="form-label">Confirm Password</label>
        <input type="password" name="password_confirmation"
               class="form-control" placeholder="Repeat password" required>
    </div>

    <button type="submit" class="btn btn-auth">Reset Password</button>
</form>
@endsection
