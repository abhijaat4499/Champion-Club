@extends('layouts.admin')

@section('title', 'Add Trainer')

@section('content')
<div class="dashboard-wrap">
    <header class="dashboard-header">
        <h1>Add Trainer</h1>
        <a href="{{ route('admin.trainers.index') }}" class="btn-logout">Back</a>
    </header>

    <form method="POST" action="{{ route('admin.trainers.store') }}" class="plan-form" enctype="multipart/form-data">
        @csrf

        <div class="form-row">
            <label for="name">Name</label>
            <input type="text" id="name" name="name" value="{{ old('name') }}" required>
            @error('name') <div class="alert-error">{{ $message }}</div> @enderror
        </div>

        <div class="form-row">
            <label for="role">Role / Specialty</label>
            <input type="text" id="role" name="role" value="{{ old('role') }}" required>
            @error('role') <div class="alert-error">{{ $message }}</div> @enderror
        </div>

        <div class="form-row">
            <label for="bio">Bio</label>
            <textarea id="bio" name="bio" rows="4" required>{{ old('bio') }}</textarea>
            @error('bio') <div class="alert-error">{{ $message }}</div> @enderror
        </div>

        <div class="form-row">
            <label for="photo">Photo</label>
            <input type="file" id="photo" name="photo" accept="image/*">
            @error('photo') <div class="alert-error">{{ $message }}</div> @enderror
        </div>

        <div class="form-row">
            <label for="display_order">Display Order</label>
            <input type="number" id="display_order" name="display_order" value="{{ old('display_order', 0) }}" required>
        </div>

        <button type="submit" class="btn-admin">Save Trainer</button>
    </form>
</div>
@endsection