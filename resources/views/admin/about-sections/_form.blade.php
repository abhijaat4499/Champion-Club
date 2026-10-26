@php
    $section = $section ?? null;
@endphp

<div class="form-row">
    <label for="eyebrow">Small label <small>(optional)</small></label>
    <input type="text" id="eyebrow" name="eyebrow" value="{{ old('eyebrow', $section->eyebrow ?? '') }}" placeholder="e.g. What's new" maxlength="60">
    <small>Short gold text shown above the heading.</small>
    @error('eyebrow') <div class="alert-error">{{ $message }}</div> @enderror
</div>

<div class="form-row">
    <label for="display_order">Display order</label>
    <input type="number" id="display_order" name="display_order" min="0" value="{{ old('display_order', $section->display_order ?? 0) }}" required>
    @error('display_order') <div class="alert-error">{{ $message }}</div> @enderror
</div>

<div class="form-row span-2">
    <label for="title">Heading</label>
    <input type="text" id="title" name="title" value="{{ old('title', $section->title ?? '') }}" placeholder="e.g. Our new recovery zone is open" maxlength="150" required>
    @error('title') <div class="alert-error">{{ $message }}</div> @enderror
</div>

<div class="form-row">
    <label for="body">Text</label>
    <textarea id="body" name="body" rows="9" maxlength="8000" required>{{ old('body', $section->body ?? '') }}</textarea>
    <small>Leave an empty line between paragraphs. A short line on its own (without a full stop) becomes a sub-heading.</small>
    @error('body') <div class="alert-error">{{ $message }}</div> @enderror
</div>

<div class="form-row">
    <label for="image">Photo <small>(optional)</small></label>
    @if($section?->image)
        <img src="{{ $section->imageUrl() }}" alt="{{ $section->title }}" class="service-thumb-preview" style="{{ $section->imageFocus() }}">
    @endif
    <input type="file" id="image" name="image" accept="image/jpeg,image/png,image/webp">
    <small>{{ \App\Support\ImageUpload::HINT }} Without a photo the text uses the full width.</small>
    @error('image') <div class="alert-error">{{ $message }}</div> @enderror
</div>

<div class="form-row">
    <label for="image_position">Image focus</label>
    <select id="image_position" name="image_position">
        @foreach(\App\Models\Service::IMAGE_POSITIONS as $value => $position)
            <option value="{{ $value }}" {{ old('image_position', $section->image_position ?? 'center') === $value ? 'selected' : '' }}>{{ $position['label'] }}</option>
        @endforeach
    </select>
    <small>Which part of the photo stays visible when it is cropped.</small>
</div>

@if($section?->image)
    <div class="checkbox-field">
        <input type="checkbox" id="remove_image" name="remove_image" value="1">
        <label for="remove_image">Remove current photo</label>
    </div>
@endif

<div class="checkbox-field">
    <input type="checkbox" id="is_active" name="is_active" value="1" {{ ($errors->any() ? old('is_active') : ($section->is_active ?? true)) ? 'checked' : '' }}>
    <label for="is_active">Show on the About page</label>
</div>
