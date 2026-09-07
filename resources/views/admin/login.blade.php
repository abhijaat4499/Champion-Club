@extends('layouts.admin')

@section('title', 'Admin Login')

@section('content')
<div class="auth-wrap">
    <form class="auth-card" method="POST" action="{{ route('admin.login.submit') }}">
        @csrf
        <h1>Admin Login</h1>
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
    </form>
</div>
@endsection