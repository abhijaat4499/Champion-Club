@php
    $offer = $offer ?? null;
    $selectedEvent = old('event', $offer->event ?? 'other');
@endphp

{{-- Event --}}
<div class="form-row">
    <span class="form-label">Choose event</span>
    <div class="event-picker" role="radiogroup" aria-label="Choose event">
        @foreach($events as $value => $label)
            <label class="event-option event-{{ $value }}">
                <input type="radio" name="event" value="{{ $value }}" {{ $selectedEvent === $value ? 'checked' : '' }} required>
                <span class="event-swatch" aria-hidden="true"></span>
                <span class="event-name">{{ $label }}</span>
            </label>
        @endforeach
    </div>
    @error('event') <div class="alert-error">{{ $message }}</div> @enderror
</div>

{{-- New Year --}}
@php $isNewYear = $selectedEvent === 'new_year'; @endphp
<div class="form-row" id="newYearRow" @if(! $isNewYear) hidden @endif>
    <label for="new_year">Which New Year?</label>
    <input type="number" id="new_year" name="new_year" inputmode="numeric"
           min="{{ $newYearRange[0] }}" max="{{ $newYearRange[1] }}" step="1"
           value="{{ old('new_year', $offer->new_year ?? \App\Models\Offer::suggestedNewYear()) }}"
           placeholder="e.g. {{ \App\Models\Offer::suggestedNewYear() }}" @disabled(! $isNewYear) required>
    <small>Type the year, e.g. {{ \App\Models\Offer::suggestedNewYear() }}. Card will show: <strong id="newYearPreview">Happy New Year {{ old('new_year', $offer->new_year ?? \App\Models\Offer::suggestedNewYear()) }}</strong></small>
    @error('new_year') <div class="alert-error">{{ $message }}</div> @enderror
</div>

<script>
    (function () {
        const row = document.getElementById('newYearRow');
        const input = document.getElementById('new_year');
        const preview = document.getElementById('newYearPreview');

        document.querySelectorAll('input[name="event"]').forEach((radio) => {
            radio.addEventListener('change', () => {
                const isNewYear = radio.value === 'new_year' && radio.checked;
                row.hidden = !isNewYear;
                input.disabled = !isNewYear;
            });
        });

        input.addEventListener('input', () => {
            preview.textContent = 'Happy New Year ' + (input.value || '…');
        });
    })();
</script>

<div class="form-row">
    <label for="title">Offer name</label>
    <input type="text" id="title" name="title" value="{{ old('title', $offer->title ?? '') }}" placeholder="e.g. Diwali Special — 3 months membership" maxlength="120" required>
    @error('title') <div class="alert-error">{{ $message }}</div> @enderror
</div>

<div class="form-row">
    <label for="starts_on">Start date</label>
    <input type="date" id="starts_on" name="starts_on" value="{{ old('starts_on', optional($offer?->starts_on)->format('Y-m-d') ?? ($offer ? '' : \App\Models\Offer::today()->format('Y-m-d'))) }}" required>
    <small>The offer appears on the website from this day.</small>
    @error('starts_on') <div class="alert-error">{{ $message }}</div> @enderror
</div>

<div class="form-row">
    <label for="valid_until">Last date</label>
    <input type="date" id="valid_until" name="valid_until" value="{{ old('valid_until', optional($offer?->valid_until)->format('Y-m-d')) }}" required>
    @error('valid_until') <div class="alert-error">{{ $message }}</div> @enderror
</div>

<div class="form-row">
    <label for="price">Offer price (₹)</label>
    <input type="number" id="price" name="price" min="0" value="{{ old('price', $offer->price ?? '') }}" required>
    @error('price') <div class="alert-error">{{ $message }}</div> @enderror
</div>

<div class="form-row">
    <label for="original_price">Original price (₹) <small>(optional)</small></label>
    <input type="number" id="original_price" name="original_price" min="0" value="{{ old('original_price', $offer->original_price ?? '') }}">
    @error('original_price') <div class="alert-error">{{ $message }}</div> @enderror
</div>

<div class="form-row">
    <label for="description">Description</label>
    <textarea id="description" name="description" rows="5" maxlength="1000" required>{{ old('description', $offer->description ?? '') }}</textarea>
    @error('description') <div class="alert-error">{{ $message }}</div> @enderror
</div>

<div class="form-row">
    <label for="display_order">Display order</label>
    <input type="number" id="display_order" name="display_order" min="0" value="{{ old('display_order', $offer->display_order ?? 0) }}" required>
    @error('display_order') <div class="alert-error">{{ $message }}</div> @enderror
</div>

<div class="checkbox-field">
    <input type="checkbox" id="is_active" name="is_active" value="1" {{ ($errors->any() ? old('is_active') : ($offer->is_active ?? true)) ? 'checked' : '' }}>
    <label for="is_active">Show on website</label>
</div>
