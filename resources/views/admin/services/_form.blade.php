@php
    $service = $service ?? null;
@endphp

<div class="form-row">
    <label for="title">Service name</label>
    <input type="text" id="title" name="title" value="{{ old('title', $service->title ?? '') }}" placeholder="e.g. Strength & Conditioning" maxlength="120" required>
    @error('title') <div class="alert-error">{{ $message }}</div> @enderror
</div>

<div class="form-row">
    <label for="slug">Page URL <small>(optional)</small></label>
    <input type="text" id="slug" name="slug" value="{{ old('slug', $service->slug ?? '') }}" placeholder="e.g. strength-conditioning" maxlength="140">
    <small>Website link: {{ url('/services') }}/<strong>{{ old('slug', $service->slug ?? 'service-name') }}</strong> — leave empty to create it from the name.</small>
    @error('slug') <div class="alert-error">{{ $message }}</div> @enderror
</div>

<div class="form-row">
    <label for="icon">Icon</label>
    <select id="icon" name="icon" required>
        @foreach($icons as $value => $label)
            <option value="{{ $value }}" {{ old('icon', $service->icon ?? 'dumbbell') === $value ? 'selected' : '' }}>{{ $label }}</option>
        @endforeach
    </select>
    @error('icon') <div class="alert-error">{{ $message }}</div> @enderror
</div>

<div class="form-row">
    <label for="summary">Short summary</label>
    <input type="text" id="summary" name="summary" value="{{ old('summary', $service->summary ?? '') }}" placeholder="One line shown on the service card" maxlength="255" required>
    @error('summary') <div class="alert-error">{{ $message }}</div> @enderror
</div>

<div class="form-row">
    <label for="description">Description</label>
    <textarea id="description" name="description" rows="6" maxlength="5000" required>{{ old('description', $service->description ?? '') }}</textarea>
    <small>Leave an empty line between paragraphs.</small>
    @error('description') <div class="alert-error">{{ $message }}</div> @enderror
</div>

<div class="form-row">
    <label for="highlights">Highlights <small>(optional, one per line)</small></label>
    <textarea id="highlights" name="highlights" rows="4" maxlength="2000" placeholder="45-minute sessions&#10;Beginner friendly">{{ old('highlights', $service->highlights ?? '') }}</textarea>
    @error('highlights') <div class="alert-error">{{ $message }}</div> @enderror
</div>

<div class="form-row">
    <label for="image">Image <small>(optional)</small></label>
    @if($service?->image)
        <img src="{{ $service->imageUrl() }}" alt="{{ $service->title }}" class="service-thumb-preview">
    @endif
    <input type="file" id="image" name="image" accept="image/jpeg,image/png,image/webp">
    <small>{{ \App\Support\ImageUpload::HINT }} A landscape photo works best.</small>
    @error('image') <div class="alert-error">{{ $message }}</div> @enderror
</div>

<div class="form-row">
    <label for="image_position">Image focus</label>
    <select id="image_position" name="image_position">
        @foreach(\App\Models\Service::IMAGE_POSITIONS as $value => $position)
            <option value="{{ $value }}" {{ old('image_position', $service->image_position ?? 'center') === $value ? 'selected' : '' }}>{{ $position['label'] }}</option>
        @endforeach
    </select>
    <small>Which part of the photo stays visible when it is cropped. Use "Upper" for tall photos where the face is near the top.</small>
    @error('image_position') <div class="alert-error">{{ $message }}</div> @enderror
</div>

@if($service?->image)
    <div class="checkbox-field">
        <input type="checkbox" id="remove_image" name="remove_image" value="1">
        <label for="remove_image">Remove current image</label>
    </div>
@endif

<div class="form-row">
    <label for="display_order">Display order</label>
    <input type="number" id="display_order" name="display_order" min="0" value="{{ old('display_order', $service->display_order ?? 0) }}" required>
    @error('display_order') <div class="alert-error">{{ $message }}</div> @enderror
</div>

<div class="checkbox-field">
    <input type="checkbox" id="is_active" name="is_active" value="1" {{ ($errors->any() ? old('is_active') : ($service->is_active ?? true)) ? 'checked' : '' }}>
    <label for="is_active">Show on website</label>
</div>
