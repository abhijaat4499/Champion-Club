@extends('layouts.admin-panel')

@section('title', 'Edit Trainer')

@section('content')
<div class="dashboard-wrap">
    <x-admin.page-header title="Edit Trainer" icon="trainers">
        <a href="{{ route('admin.trainers.index') }}" class="btn-logout">&larr; Back to trainers</a>
    </x-admin.page-header>

    <form method="POST" action="{{ route('admin.trainers.update', $trainer) }}" class="plan-form" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="form-row">
            <label for="name">Name</label>
            <input type="text" id="name" name="name" value="{{ old('name', $trainer->name) }}" required>
            @error('name') <div class="alert-error">{{ $message }}</div> @enderror
        </div>

        <div class="form-row">
            <label for="role">Role / Specialty</label>
            <input type="text" id="role" name="role" value="{{ old('role', $trainer->role) }}" required>
            @error('role') <div class="alert-error">{{ $message }}</div> @enderror
        </div>

        <div class="form-row">
            <label for="bio">Bio</label>
            <textarea id="bio" name="bio" rows="4" required>{{ old('bio', $trainer->bio) }}</textarea>
            @error('bio') <div class="alert-error">{{ $message }}</div> @enderror
        </div>

        <div class="form-row">
            <label for="photo">Photo</label>
            @if($trainer->photo)
                <img src="{{ asset('storage/' . $trainer->photo) }}" alt="{{ $trainer->name }}" class="trainer-thumb">
            @endif
            <input type="file" id="photo" name="photo" accept="image/jpeg,image/png,image/webp">
            <small>{{ \App\Support\ImageUpload::HINT }} A portrait photo with the face near the top works best.</small>
            @error('photo') <div class="alert-error">{{ $message }}</div> @enderror
        </div>

        <div class="form-row">
            <label for="display_order">Display Order</label>
            <input type="number" id="display_order" name="display_order" value="{{ old('display_order', $trainer->display_order) }}" required>
            @error('display_order') <div class="alert-error">{{ $message }}</div> @enderror
        </div>

        <button type="submit" class="btn-admin">Update Trainer</button>
    </form>
</div>
@endsection
