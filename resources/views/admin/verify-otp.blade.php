@extends('layouts.admin')

@section('title', 'Verify Code')

@section('content')
<div class="auth-wrap">
    <form class="auth-card" method="POST" action="{{ route('admin.otp.verify') }}">
        @csrf
        <h1>Enter Verification Code</h1>
        <p class="auth-sub">We've emailed a 6-digit code. It expires in {{ \App\Support\AdminOtp::VALID_MINUTES }} minutes.</p>

        @if(session('success'))
            <div class="alert-success">{{ session('success') }}</div>
        @endif

        @error('otp')
            <div class="alert-error">{{ $message }}</div>
        @enderror

        <div class="form-row">
            <label for="otp">Verification Code</label>
            <input type="text" id="otp" name="otp" inputmode="numeric" maxlength="6" autocomplete="one-time-code" required autofocus>
        </div>

        <button type="submit" class="btn-admin">Verify & Login</button>

        @include('admin.partials.otp-resend')

        <p class="auth-link"><a href="{{ route('admin.login') }}">Back to login</a></p>
    </form>

    @include('admin.partials.otp-resend-form', ['action' => route('admin.otp.resend')])
</div>
@endsection
