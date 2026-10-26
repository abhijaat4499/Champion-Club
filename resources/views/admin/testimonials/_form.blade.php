@php
    $review = $review ?? null;
@endphp

<div class="form-row">
    <label for="name">Member name</label>
    <input type="text" id="name" name="name" value="{{ old('name', $review->name ?? '') }}" maxlength="80" required>
    @error('name') <div class="alert-error">{{ $message }}</div> @enderror
</div>

<div class="form-row">
    <label for="quote">Review</label>
    <textarea id="quote" name="quote" rows="5" maxlength="600" required>{{ old('quote', $review->quote ?? '') }}</textarea>
    @error('quote') <div class="alert-error">{{ $message }}</div> @enderror
</div>

<div class="form-row">
    <label for="rating">Rating</label>
    <select id="rating" name="rating" required>
        @for($stars = 5; $stars >= 1; $stars--)
            <option value="{{ $stars }}" {{ (int) old('rating', $review->rating ?? 5) === $stars ? 'selected' : '' }}>{{ $stars }} {{ $stars === 1 ? 'star' : 'stars' }}</option>
        @endfor
    </select>
    @error('rating') <div class="alert-error">{{ $message }}</div> @enderror
</div>

<div class="form-row">
    <label for="display_order">Display order</label>
    <input type="number" id="display_order" name="display_order" min="0" value="{{ old('display_order', $review->display_order ?? 0) }}" required>
    @error('display_order') <div class="alert-error">{{ $message }}</div> @enderror
</div>

<div class="checkbox-field">
    <input type="checkbox" id="is_active" name="is_active" value="1" {{ ($errors->any() ? old('is_active') : ($review->is_active ?? true)) ? 'checked' : '' }}>
    <label for="is_active">Show on website</label>
</div>
