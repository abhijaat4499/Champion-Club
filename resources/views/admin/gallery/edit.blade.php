@extends('layouts.admin-panel')

@section('title', 'Edit Photo')

@section('content')
<div class="dashboard-wrap">

    <x-admin.page-header title="Edit photo" icon="gallery">
        <a href="{{ route('admin.gallery.index') }}" class="btn-logout">&larr; Back to gallery</a>
    </x-admin.page-header>

    <form method="POST" action="{{ route('admin.gallery.update', $photo) }}" class="plan-form" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="form-row">
            <label>Current photo</label>
            <img src="{{ $photo->url() }}" alt="{{ $photo->alt }}" class="gallery-edit-preview">
        </div>

        <div class="form-row">
            <label for="image">Replace photo <small>(optional)</small></label>
            <input type="file" id="image" name="image" accept="image/jpeg,image/png,image/webp">
            <small>{{ \App\Support\ImageUpload::HINT }}</small>
            @error('image') <div class="alert-error">{{ $message }}</div> @enderror
        </div>

        <div class="form-row">
            <label for="alt">Alt text</label>
            <input type="text" id="alt" name="alt" value="{{ old('alt', $photo->alt) }}" maxlength="160" required>
            <small>Describe what is in the photo, e.g. "Treadmills beside large windows".</small>
            @error('alt') <div class="alert-error">{{ $message }}</div> @enderror
        </div>

        <div class="form-row">
            <label for="caption">Caption <small>(optional)</small></label>
            <input type="text" id="caption" name="caption" value="{{ old('caption', $photo->caption) }}" maxlength="160">
            @error('caption') <div class="alert-error">{{ $message }}</div> @enderror
        </div>

        <div class="checkbox-field">
            <input type="checkbox" id="is_active" name="is_active" value="1" {{ ($errors->any() ? old('is_active') : $photo->is_active) ? 'checked' : '' }}>
            <label for="is_active">Show on website</label>
        </div>

        <button type="submit" class="btn-admin">Update Photo</button>
    </form>
</div>
@endsection
