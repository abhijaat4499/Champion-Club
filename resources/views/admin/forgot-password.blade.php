@extends('layouts.admin')

@section('title', 'Forgot Password')

@section('content')
<div class="auth-wrap">
    <form data-sends-code class="auth-card" method="POST" action="{{ route('admin.password.email') }}">
        @csrf
        <h1>Forgot Password</h1>
        <p class="auth-sub">Enter your admin email and we'll send you a verification code.</p>

        @error('email')
            <div class="alert-error">{{ $message }}</div>
        @enderror

        <div class="form-row">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus>
        </div>

        <button type="submit" class="btn-admin">Send code</button>

        <p class="auth-link"><a href="{{ route('admin.login') }}">Back to login</a></p>
    </form>
</div>
@endsection