@extends('layouts.app')

@section('title', 'Contact ChampionClub — Gym in ' . config('gym.address.locality') . ' | Call, WhatsApp or Visit')
@section('description', 'Contact ChampionClub in ' . config('gym.address.locality') . '. Call ' . $gymContact->displayPhone() . ', message us on WhatsApp or visit us at ' . $gymContact->addressLine() . '.')
@section('og_image', 'page-contact')

@php $selectedInterest = old('interest', request('interest')); @endphp

@section('content')

<x-site.page-hero image="page-contact" eyebrow="Contact" title="Let's talk training." crumb="Contact">
    Questions about membership, programs, or just want to see the floor first? Reach out, or walk in during opening hours.
</x-site.page-hero>

<section class="bg-bone-100 pt-12 pb-14 text-ink-950 lg:pt-14 lg:pb-16">
    <div class="wrap grid gap-10 lg:grid-cols-12 lg:gap-14">
        <div class="lg:col-span-7" data-reveal>
            <form action="{{ route('store.contact') }}" method="POST" class="contact-form border border-ink-950/10 bg-white p-7 shadow-[0_30px_60px_-40px_rgba(9,9,10,0.45)] sm:p-10" novalidate>
                @csrf
                <h2 class="display-md">Send a message</h2>
                <p class="mt-2 mb-8 text-ink-950/60">Share a phone number or an email — we'll get back to you shortly.</p>

                @if($errors->any())
                    <div class="mb-6 border-l-4 border-ember-500 bg-ember-500/10 px-4 py-3 text-sm font-medium text-ember-600" role="alert">Please check the highlighted fields.</div>
                @endif

                <div class="grid gap-5 sm:grid-cols-2">
                    <div class="sm:col-span-2">
                        <label for="name" class="field-label">Name</label>
                        <input type="text" id="name" name="name" value="{{ old('name') }}" autocomplete="name" required class="field field-light" placeholder="Your full name" @error('name') aria-invalid="true" @enderror>
                        @error('name') <span class="field-error">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label for="phone" class="field-label">Mobile number</label>
                        <input type="tel" id="phone" name="phone" value="{{ old('phone') }}" autocomplete="tel-national" inputmode="numeric" maxlength="10" data-phone class="field field-light" placeholder="10-digit mobile number" @error('phone') aria-invalid="true" @enderror>
                        @error('phone') <span class="field-error">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label for="email" class="field-label">Email <span class="font-normal text-ink-950/45">(optional)</span></label>
                        <input type="email" id="email" name="email" value="{{ old('email') }}" autocomplete="email" class="field field-light" placeholder="you@example.com" @error('email') aria-invalid="true" @enderror>
                        @error('email') <span class="field-error">{{ $message }}</span> @enderror
                    </div>

                    <div class="sm:col-span-2">
                        <label for="interest" class="field-label">I'm interested in</label>
                        <select id="interest" name="interest" class="field field-light">
                            <option value="" disabled {{ $selectedInterest ? '' : 'selected' }}>Select an option</option>
                            @foreach(\App\Models\Contact::INTERESTS as $value => $label)
                                <option value="{{ $value }}" {{ $selectedInterest == $value ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                        @error('interest') <span class="field-error">{{ $message }}</span> @enderror
                    </div>

                    <div class="sm:col-span-2">
                        <label for="message" class="field-label">Message <span class="font-normal text-ink-950/45">(optional)</span></label>
                        <textarea id="message" name="message" rows="5" class="field field-light resize-y" placeholder="Tell us what you're looking for">{{ old('message') }}</textarea>
                        @error('message') <span class="field-error">{{ $message }}</span> @enderror
                    </div>
                </div>

                <button type="submit" class="btn btn-dark mt-7 w-full sm:w-auto">Send message @icon('arrow', 'btn-arrow')</button>
            </form>
        </div>

        <aside class="grid content-start gap-4 lg:col-span-5">
            @foreach([
                ['icon' => 'phone', 'title' => 'Call us', 'value' => $gymContact->displayPhone(), 'href' => $gymContact->callUrl(), 'external' => false],
                ['icon' => 'whatsapp', 'title' => 'WhatsApp', 'value' => 'Chat with our team', 'href' => $gymContact->whatsappUrl(), 'external' => true],
                ['icon' => 'mail', 'title' => 'Email', 'value' => $gymContact->email(), 'href' => 'mailto:' . $gymContact->email(), 'external' => false],
                ['icon' => 'pin', 'title' => 'Visit us', 'value' => $gymContact->addressLine(), 'href' => $gymContact->mapsUrl(), 'external' => true],
            ] as $c)
                <a href="{{ $c['href'] }}" @if($c['external']) target="_blank" rel="noopener" @endif class="group flex items-center gap-5 border border-ink-950/10 bg-bone-50 p-5 transition hover:border-ink-950 hover:bg-white" data-reveal style="--reveal-delay: {{ $loop->index * 0.06 }}s">
                    <span class="inline-flex size-12 shrink-0 items-center justify-center rounded-md bg-ink-950 text-xl text-gold-400">@icon($c['icon'], '')</span>
                    <span class="min-w-0 flex-1">
                        <span class="block text-xs font-bold tracking-[0.16em] text-ink-950/50 uppercase">{{ $c['title'] }}</span>
                        <span class="mt-0.5 block font-semibold break-words">{{ $c['value'] }}</span>
                    </span>
                    @icon('arrow', 'text-ink-950/30 transition group-hover:translate-x-1 group-hover:text-ink-950')
                </a>
            @endforeach

            <div class="bg-ink-950 p-7 text-white" data-reveal>
                <p class="flex items-center gap-3 text-xs font-bold tracking-[0.16em] text-gold-400 uppercase">@icon('clock', 'text-base') Opening hours</p>
                <ul class="mt-5 space-y-3">
                    @foreach($gymContact->hours() as $h)
                        <li class="flex justify-between gap-4 border-b border-white/10 pb-3 last:border-0 last:pb-0"><span class="text-white/65">{{ $h['days'] }}</span><span class="font-semibold">{{ $h['time'] }}</span></li>
                    @endforeach
                </ul>
            </div>
        </aside>
    </div>
</section>

<section class="bg-bone-100 pb-14 lg:pb-16" aria-label="Map">
    <div class="wrap">
        <div class="aspect-[4/3] overflow-hidden border border-ink-950/10 sm:aspect-[21/9]">
            @include('partials.site.map')
        </div>
    </div>
</section>

@endsection
