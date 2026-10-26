@extends('layouts.admin-panel')

@section('title', 'Change Password')

@section('content')
<div class="dashboard-wrap">

    <x-admin.page-header title="Change Password" icon="password" />

    <form method="POST" action="{{ route('admin.password.change.update') }}" class="plan-form">
        @csrf
        @method('PUT')

        @if(session('success'))
            <div class="alert-success">{{ session('success') }}</div>
        @endif


        <div class="form-row">
            <label for="password">New password</label>
            <input type="password" id="password" name="password" autocomplete="new-password" required autofocus>
            @error('password') <div class="alert-error">{{ $message }}</div> @enderror
        </div>

        <div class="form-row">
            <label for="password_confirmation">Confirm new password</label>
            <input type="password" id="password_confirmation" name="password_confirmation" autocomplete="new-password" required>
        </div>

        <button type="submit" class="btn-admin">Update password</button>
    </form>
</div>
@endsection
