@extends('layouts.admin')

@section('title', 'New Password')

@section('content')
<div class="auth-wrap">
    <form class="auth-card" method="POST" action="{{ route('admin.password.update') }}">
        @csrf
        <h1>New Password</h1>
        <p class="auth-sub">Choose a new password (at least 8 characters).</p>

        @error('password')
            <div class="alert-error">{{ $message }}</div>
        @enderror

        <div class="form-row">
            <label for="password">New password</label>
            <input type="password" id="password" name="password" required autofocus>
        </div>

        <div class="form-row">
            <label for="password_confirmation">Confirm password</label>
            <input type="password" id="password_confirmation" name="password_confirmation" required>
        </div>

        <button type="submit" class="btn-admin">Update password</button>
    </form>
</div>
@endsection