@php
    $theme = $theme ?? 'dark';
    $fieldClass = $theme === 'dark' ? 'field field-dark' : 'field field-light';
    $labelClass = $theme === 'dark' ? 'field-label text-white/80' : 'field-label text-ink-950';
    $defaultInterest = $interest ?? 'trial';
    $selectedInterest = old('interest', request('interest', $defaultInterest));
    $formId = $formId ?? 'lead';
    $button = $button ?? 'Claim my free session';
    $booking = $booking ?? null;
@endphp

<form action="{{ route('store.contact') }}" method="POST" class="grid gap-5" novalidate>
    @csrf

    @if($booking)
        <input type="hidden" name="ref_type" value="{{ $booking['type'] }}">
        <input type="hidden" name="ref_id" value="{{ $booking['id'] }}">
        <input type="hidden" name="interest" value="{{ $booking['interest'] }}">
        <div>
            <span class="{{ $labelClass }}">Enquiring about</span>
            <p class="flex items-start gap-3 rounded-sm border px-4 py-3.5 text-[0.95rem] font-semibold {{ $theme === 'dark' ? 'border-gold-500/40 bg-gold-500/10 text-white' : 'border-gold-600/40 bg-gold-500/10 text-ink-950' }}">
                @icon('check', 'mt-0.5 shrink-0 text-gold-500')
                <span>{{ $booking['subject'] }}</span>
            </p>
        </div>
    @endif

    <div>
        <label for="{{ $formId }}-name" class="{{ $labelClass }}">Your name</label>
        <input type="text" id="{{ $formId }}-name" name="name" value="{{ old('name') }}" autocomplete="name" required
               class="{{ $fieldClass }}" placeholder="Full name"
               @error('name') aria-invalid="true" aria-describedby="{{ $formId }}-name-error" @enderror>
        @error('name') <span id="{{ $formId }}-name-error" class="field-error">{{ $message }}</span> @enderror
    </div>

    <div>
        <label for="{{ $formId }}-phone" class="{{ $labelClass }}">Mobile number</label>
        <input type="tel" id="{{ $formId }}-phone" name="phone" value="{{ old('phone') }}" autocomplete="tel-national" inputmode="numeric" maxlength="10" required data-phone
               class="{{ $fieldClass }}" placeholder="10-digit mobile number"
               @error('phone') aria-invalid="true" aria-describedby="{{ $formId }}-phone-error" @enderror>
        @error('phone') <span id="{{ $formId }}-phone-error" class="field-error">{{ $message }}</span> @enderror
    </div>

    @unless($booking)
        <div>
            <label for="{{ $formId }}-interest" class="{{ $labelClass }}">I'm interested in</label>
            <select id="{{ $formId }}-interest" name="interest" class="{{ $fieldClass }}">
                @foreach(\App\Models\Contact::INTERESTS as $value => $label)
                    <option value="{{ $value }}" {{ $selectedInterest == $value ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
            </select>
        </div>
    @endunless

    <button type="submit" class="btn btn-gold mt-1 w-full">{{ $button }} @icon('arrow', 'btn-arrow')</button>
    <p class="text-xs {{ $theme === 'dark' ? 'text-white/45' : 'text-ink-950/55' }}">No spam. Our team will get back to you shortly.</p>
</form>
