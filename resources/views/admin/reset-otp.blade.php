@extends('layouts.admin')

@section('title', 'Verify Code')

@section('content')
<div class="auth-wrap">
    <form class="auth-card" method="POST" action="{{ route('admin.password.verify') }}">
        @csrf
        <h1>Enter Code</h1>
        <p class="auth-sub">We've emailed a 6-digit code. It expires in {{ \App\Support\AdminOtp::VALID_MINUTES }} minutes.</p>

        @if(session('success'))
            <div class="alert-success">{{ session('success') }}</div>
        @endif

        @error('otp')
            <div class="alert-error">{{ $message }}</div>
        @enderror

        <div class="form-row">
            <label for="otp">6-digit code</label>
            <input type="text" id="otp" name="otp" maxlength="6" inputmode="numeric" autocomplete="one-time-code" required autofocus>
        </div>

        <button type="submit" class="btn-admin">Verify code</button>

        @include('admin.partials.otp-resend')

        <p class="auth-link"><a href="{{ route('admin.password.request') }}">Use a different email</a></p>
    </form>

    @include('admin.partials.otp-resend-form', ['action' => route('admin.password.resend')])
</div>
@endsection
