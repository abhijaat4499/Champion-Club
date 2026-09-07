@extends('layouts.admin')

@section('title', 'Verify Code')

@section('content')
<div class="auth-wrap">
    <form class="auth-card" method="POST" action="{{ route('admin.otp.verify') }}">
        @csrf
        <h1>Enter Verification Code</h1>
        <p class="auth-sub">We've emailed a 6-digit code. It expires in 10 minutes.</p>

        @error('otp')
            <div class="alert-error">{{ $message }}</div>
        @enderror

        <div class="form-row">
            <label for="otp">Verification Code</label>
            <input type="text" id="otp" name="otp" inputmode="numeric" maxlength="6" required autofocus>
        </div>

        <button type="submit" class="btn-admin">Verify & Login</button>
    </form>
</div>
@endsection