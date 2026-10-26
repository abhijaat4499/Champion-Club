@php
    $facility = $facility ?? null;
@endphp

<div class="form-row">
    <label for="title">Facility name</label>
    <input type="text" id="title" name="title" value="{{ old('title', $facility->title ?? '') }}" placeholder="e.g. Cardio zone" maxlength="80" required>
    @error('title') <div class="alert-error">{{ $message }}</div> @enderror
</div>

<div class="form-row">
    <label for="display_order">Display order</label>
    <input type="number" id="display_order" name="display_order" min="0" value="{{ old('display_order', $facility->display_order ?? 0) }}" required>
    @error('display_order') <div class="alert-error">{{ $message }}</div> @enderror
</div>

<div class="form-row">
    <label for="text">Short description</label>
    <textarea id="text" name="text" rows="3" maxlength="300" required>{{ old('text', $facility->text ?? '') }}</textarea>
    <small>One or two lines shown on the photo.</small>
    @error('text') <div class="alert-error">{{ $message }}</div> @enderror
</div>

<div class="form-row">
    <label for="image">Photo <small>(optional)</small></label>
    @if($facility?->image)
        <img src="{{ $facility->imageUrl() }}" alt="{{ $facility->title }}" class="service-thumb-preview" style="{{ $facility->imageFocus() }}">
    @endif
    <input type="file" id="image" name="image" accept="image/jpeg,image/png,image/webp">
    <small>{{ \App\Support\ImageUpload::HINT }}</small>
    @error('image') <div class="alert-error">{{ $message }}</div> @enderror
</div>

<div class="form-row">
    <label for="image_position">Image focus</label>
    <select id="image_position" name="image_position">
        @foreach(\App\Models\Service::IMAGE_POSITIONS as $value => $position)
            <option value="{{ $value }}" {{ old('image_position', $facility->image_position ?? 'center') === $value ? 'selected' : '' }}>{{ $position['label'] }}</option>
        @endforeach
    </select>
    <small>Which part of the photo stays visible when it is cropped.</small>
</div>

@if($facility?->image)
    <div class="checkbox-field">
        <input type="checkbox" id="remove_image" name="remove_image" value="1">
        <label for="remove_image">Remove current photo</label>
    </div>
@endif

<div class="checkbox-field">
    <input type="checkbox" id="is_active" name="is_active" value="1" {{ ($errors->any() ? old('is_active') : ($facility->is_active ?? true)) ? 'checked' : '' }}>
    <label for="is_active">Show on website</label>
</div>
