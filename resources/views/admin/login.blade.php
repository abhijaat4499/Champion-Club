@extends('layouts.admin')

@section('title', 'Admin Login')

@section('content')
<div class="auth-wrap">
    <form data-sends-code class="auth-card" method="POST" action="{{ route('admin.login.submit') }}">
        @csrf
        <h1>Admin Login</h1>
        @if(session('success'))
    <div class="alert-success">{{ session('success') }}</div>
@endif
        <p class="auth-sub">Enter your credentials to receive a login code.</p>

        @error('email')
            <div class="alert-error">{{ $message }}</div>
        @enderror

        <div class="form-row">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus>
        </div>

        <div class="form-row">
            <label for="password">Password</label>
            <input type="password" id="password" name="password" required>
        </div>

        <button type="submit" class="btn-admin">Send login code</button>
        <p class="auth-link"><a href="{{ route('admin.password.request') }}">Forgot password?</a></p>
    </form>
</div>
@endsection