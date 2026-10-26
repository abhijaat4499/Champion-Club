@php
    $video = $video ?? null;
@endphp

<div class="form-row span-2">
    <label for="youtube_url">YouTube video link</label>
    <input type="url" id="youtube_url" name="youtube_url" value="{{ old('youtube_url', $video ? $video->watchUrl() : '') }}" placeholder="https://www.youtube.com/watch?v=..." required>
    <small>Paste the link of a video, a YouTube Short or a youtu.be link.</small>
    @error('youtube_id') <div class="alert-error">{{ $message }}</div> @enderror
    @error('youtube_url') <div class="alert-error">{{ $message }}</div> @enderror
</div>

<div class="form-row">
    <label for="title">Video title</label>
    <input type="text" id="title" name="title" value="{{ old('title', $video->title ?? '') }}" placeholder="e.g. Lost 12 kg in 4 months" maxlength="120" required>
    @error('title') <div class="alert-error">{{ $message }}</div> @enderror
</div>

<div class="form-row">
    <label for="name">Member name <small>(optional)</small></label>
    <input type="text" id="name" name="name" value="{{ old('name', $video->name ?? '') }}" maxlength="80">
    @error('name') <div class="alert-error">{{ $message }}</div> @enderror
</div>

<div class="form-row">
    <label for="display_order">Display order</label>
    <input type="number" id="display_order" name="display_order" min="0" value="{{ old('display_order', $video->display_order ?? 0) }}" required>
    @error('display_order') <div class="alert-error">{{ $message }}</div> @enderror
</div>

@if($video)
    <div class="form-row">
        <label>Preview</label>
        <img src="{{ $video->thumbnailUrl() }}" alt="{{ $video->title }}" class="service-thumb-preview">
    </div>
@endif

<div class="checkbox-field">
    <input type="checkbox" id="is_active" name="is_active" value="1" {{ ($errors->any() ? old('is_active') : ($video->is_active ?? true)) ? 'checked' : '' }}>
    <label for="is_active">Show on website</label>
</div>
