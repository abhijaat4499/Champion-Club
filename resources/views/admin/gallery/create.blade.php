@extends('layouts.admin-panel')

@section('title', 'Upload Photos')

@section('content')
<div class="dashboard-wrap">

    <x-admin.page-header title="Upload photos" icon="gallery">
        <a href="{{ route('admin.gallery.index') }}" class="btn-logout">&larr; Back to gallery</a>
    </x-admin.page-header>

    @if($errors->any())
        <div class="alert-error settings-errors">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('admin.gallery.store') }}" class="plan-form" enctype="multipart/form-data">
        @csrf

        <div class="form-row">
            <label for="photos">Photos</label>
            <input type="file" id="photos" name="photos[]" accept="image/jpeg,image/png,image/webp" multiple required>
            <small>Select one or more JPG, PNG or WebP photos (up to 25 MB each, 20 at a time). Large photos are resized and compressed automatically. If a big batch fails, upload fewer photos at once.</small>
        </div>

        <div class="form-row">
            <label for="alt">Alt text for all uploaded photos <small>(optional)</small></label>
            <input type="text" id="alt" name="alt" value="{{ old('alt') }}" maxlength="160" placeholder="e.g. Cardio section with treadmills">
            <small>Leave blank to use the file names; you can edit each photo afterwards.</small>
        </div>

        <div class="form-row">
            <label for="caption">Caption <small>(optional, applied to all)</small></label>
            <input type="text" id="caption" name="caption" value="{{ old('caption') }}" maxlength="160" placeholder="e.g. Cardio section">
        </div>

        <div class="checkbox-field">
            <input type="checkbox" id="is_active" name="is_active" value="1" {{ ($errors->any() ? old('is_active') : true) ? 'checked' : '' }}>
            <label for="is_active">Publish immediately</label>
        </div>

        <button type="submit" class="btn-admin">Upload</button>
    </form>
</div>
@endsection
