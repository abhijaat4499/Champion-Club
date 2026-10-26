@php
    $slide = $slide ?? null;
    $pattern = old('pattern', $slide->pattern ?? 'spotlight');
@endphp

{{-- ---------- Layout ---------- --}}
<div class="form-row span-2">
    <span class="form-label">Pattern</span>
    <div class="pattern-picker" role="radiogroup" aria-label="Pattern">
        @foreach(\App\Models\HeroSlide::PATTERNS as $value => $info)
            <label class="pattern-option">
                <input type="radio" name="pattern" value="{{ $value }}" {{ $pattern === $value ? 'checked' : '' }} required>
                <span class="pattern-preview pattern-preview-{{ $value }}" aria-hidden="true"><i></i><i></i><i></i></span>
                <span class="pattern-name">{{ $info['label'] }}</span>
                <span class="pattern-hint">{{ $info['hint'] }}</span>
            </label>
        @endforeach
    </div>
    @error('pattern') <div class="alert-error">{{ $message }}</div> @enderror
</div>

{{-- ---------- Text ---------- --}}
<div class="form-row">
    <label for="label">Small label <small>(optional)</small></label>
    <input type="text" id="label" name="label" value="{{ old('label', $slide->label ?? '') }}" maxlength="80" placeholder="e.g. Personal coaching">
    <small>Short gold text above the heading.</small>
    @error('label') <div class="alert-error">{{ $message }}</div> @enderror
</div>

<div class="form-row">
    <label for="tab">Tab name</label>
    <input type="text" id="tab" name="tab" value="{{ old('tab', $slide->tab ?? '') }}" maxlength="24" placeholder="e.g. Coaching" required>
    <small>One or two words shown on the slide progress bar.</small>
    @error('tab') <div class="alert-error">{{ $message }}</div> @enderror
</div>

<div class="form-row span-2">
    <label for="title">Heading</label>
    <input type="text" id="title" name="title" value="{{ old('title', $slide->title ?? '') }}" maxlength="160" required>
    @error('title') <div class="alert-error">{{ $message }}</div> @enderror
</div>

<div class="form-row span-2">
    <label for="highlight">Gold words in the heading <small>(optional)</small></label>
    <input type="text" id="highlight" name="highlight" value="{{ old('highlight', $slide->highlight ?? '') }}" maxlength="80" placeholder="e.g. refused to quit.">
    <small>Copy a few words exactly from the heading to show them in gold.</small>
    @error('highlight') <div class="alert-error">{{ $message }}</div> @enderror
</div>

<div class="form-row span-2">
    <label for="description">Description <small>(optional)</small></label>
    <textarea id="description" name="description" rows="3" maxlength="400">{{ old('description', $slide->description ?? '') }}</textarea>
    @error('description') <div class="alert-error">{{ $message }}</div> @enderror
</div>

{{-- ---------- Points ---------- --}}
<div class="form-row">
    <label for="points_style">Points under the description</label>
    <select id="points_style" name="points_style">
        @foreach(\App\Models\HeroSlide::POINT_STYLES as $value => $label)
            <option value="{{ $value }}" {{ old('points_style', $slide->points_style ?? 'none') === $value ? 'selected' : '' }}>{{ $label }}</option>
        @endforeach
    </select>
    <small>Capsules are rounded tags; check points are a list with ticks.</small>
</div>

<div class="form-row">
    <label for="image_position">Image focus</label>
    <select id="image_position" name="image_position">
        @foreach(\App\Models\HeroSlide::POSITIONS as $value => $position)
            <option value="{{ $value }}" {{ old('image_position', $slide->image_position ?? 'center') === $value ? 'selected' : '' }}>{{ $position['label'] }}</option>
        @endforeach
    </select>
    <small>Which part of the photo stays visible on wide screens and phones. Use Left or Right if the person is on one side.</small>
</div>

<div class="form-row">
    <label for="points">Points <small>(optional, one per line)</small></label>
    <textarea id="points" name="points" rows="4" maxlength="1000" placeholder="Free weights&#10;Lockers & showers">{{ old('points', $slide->points ?? '') }}</textarea>
    @error('points') <div class="alert-error">{{ $message }}</div> @enderror
</div>

{{-- ---------- Photo ---------- --}}
<div class="form-row">
    <label for="image">Photo {{ $slide ? '(optional)' : '' }}</label>
    @if($slide?->image)
        <img src="{{ asset('storage/' . $slide->image) }}" alt="" class="hero-photo-preview" style="{{ $slide->focus() }}">
    @elseif($slide?->site_image)
        <img src="{{ asset(config('site-images.' . $slide->site_image . '.sm')) }}" alt="" class="hero-photo-preview">
    @endif
    <input type="file" id="image" name="image" accept="image/jpeg,image/png,image/webp" {{ $slide ? '' : 'required' }}>
    <small>{{ \App\Support\ImageUpload::HINT }} A wide (landscape) photo works best; a smaller copy is made for phones.</small>
    @error('image') <div class="alert-error">{{ $message }}</div> @enderror
</div>

{{-- ---------- Buttons ---------- --}}
<div class="form-row">
    <label for="button_text">First button text <small>(optional)</small></label>
    <input type="text" id="button_text" name="button_text" value="{{ old('button_text', $slide->button_text ?? '') }}" maxlength="40" placeholder="e.g. Book a free trial">
    @error('button_text') <div class="alert-error">{{ $message }}</div> @enderror
</div>

<div class="form-row">
    <label for="button_target">First button goes to</label>
    <select id="button_target" name="button_target">
        <option value="">—</option>
        @foreach(\App\Models\HeroSlide::TARGETS as $value => $label)
            <option value="{{ $value }}" {{ old('button_target', $slide->button_target ?? '') === $value ? 'selected' : '' }}>{{ $label }}</option>
        @endforeach
    </select>
    @error('button_target') <div class="alert-error">{{ $message }}</div> @enderror
</div>

<div class="form-row">
    <label for="button2_text">Second button text <small>(optional)</small></label>
    <input type="text" id="button2_text" name="button2_text" value="{{ old('button2_text', $slide->button2_text ?? '') }}" maxlength="40" placeholder="e.g. Chat with us">
    @error('button2_text') <div class="alert-error">{{ $message }}</div> @enderror
</div>

<div class="form-row">
    <label for="button2_target">Second button goes to</label>
    <select id="button2_target" name="button2_target">
        <option value="">—</option>
        @foreach(\App\Models\HeroSlide::TARGETS as $value => $label)
            <option value="{{ $value }}" {{ old('button2_target', $slide->button2_target ?? '') === $value ? 'selected' : '' }}>{{ $label }}</option>
        @endforeach
    </select>
    @error('button2_target') <div class="alert-error">{{ $message }}</div> @enderror
</div>

<div class="form-row">
    <label for="display_order">Display order</label>
    <input type="number" id="display_order" name="display_order" min="0" value="{{ old('display_order', $slide->display_order ?? 0) }}" required>
</div>

<div class="checkbox-field">
    <input type="checkbox" id="is_active" name="is_active" value="1" {{ ($errors->any() ? old('is_active') : ($slide->is_active ?? true)) ? 'checked' : '' }}>
    <label for="is_active">Show on the home page</label>
</div>
