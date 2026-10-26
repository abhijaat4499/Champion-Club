@extends('layouts.app')

@section('title', 'Gym Facilities & Equipment — 20,000 sq ft | ChampionClub ' . config('gym.address.locality'))
@section('description', 'Explore the ChampionClub training floor in ' . config('gym.address.locality') . ': lifting platforms, free weights, functional zone, cardio zone, lockers and showers.')
@section('og_image', 'page-facilities')

@section('content')

<x-site.page-hero image="page-facilities" eyebrow="Facilities" title="20,000 sq ft built for serious training." crumb="Facilities">
    Competition-grade equipment, zones for every kind of training, and lockers and showers included with every plan.
    <x-slot:actions>
        <a href="{{ route('gym.trial') }}" class="btn btn-gold">Book a free trial @icon('arrow', 'btn-arrow')</a>
        <a href="{{ $gymContact->mapsUrl() }}" target="_blank" rel="noopener" class="btn btn-ghost">@icon('pin', '') Get directions</a>
    </x-slot:actions>
</x-site.page-hero>

<section class="bg-bone-100 pt-12 pb-14 text-ink-950 lg:pt-14 lg:pb-16">
    <div class="wrap">
        <div class="grid gap-8 lg:grid-cols-12 lg:items-end">
            <div class="lg:col-span-7" data-reveal>
                <p class="eyebrow !text-gold-700">The floor</p>
                <h2 class="display-lg mt-5">Every zone has a job.</h2>
            </div>
            <p class="text-ink-950/70 lg:col-span-5" data-reveal>From heavy barbell work to cardio, the floor is laid out so you can move from strength to conditioning without waiting around.</p>
        </div>

        @if($facilities->isEmpty())
            <p class="mt-14 border border-ink-950/10 bg-white p-10 text-center text-ink-950/65">Facility details will be added soon.</p>
        @else
            <div class="mt-14 grid gap-5 md:grid-cols-2">
                @foreach($facilities as $facility)
                    @php $wide = $loop->first || ($loop->last && $loop->count % 2 === 0); @endphp
                    <figure class="zoom-media group relative overflow-hidden bg-ink-900 {{ $wide ? 'md:col-span-2' : '' }}" data-reveal="zoom" style="--reveal-delay: {{ ($loop->index % 2) * 0.08 }}s">
                        <div class="relative overflow-hidden {{ $wide ? 'aspect-[4/3] md:aspect-[21/9]' : 'aspect-[4/3]' }}">
                            @include('partials.site.facility-image', ['facility' => $facility, 'class' => 'absolute inset-0 size-full'])
                            <div class="absolute inset-0 bg-gradient-to-t from-ink-950 via-ink-950/20 to-transparent"></div>
                        </div>
                        <figcaption class="absolute inset-x-0 bottom-0 flex items-end justify-between gap-6 p-6 sm:p-8">
                            <div>
                                <span class="font-display text-sm font-bold tracking-[0.2em] text-gold-400">{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>
                                <h3 class="mt-1 font-display text-3xl leading-tight font-bold text-white sm:text-4xl">{{ $facility->title }}</h3>
                                <p class="mt-2 max-w-md text-[0.95rem] text-white/75">{{ $facility->text }}</p>
                            </div>
                        </figcaption>
                    </figure>
                @endforeach
            </div>
        @endif
    </div>
</section>

<section class="bg-ink-950 pt-12 pb-14 lg:pt-14 lg:pb-16">
    <div class="wrap grid gap-12 lg:grid-cols-12">
        <div class="lg:col-span-5" data-reveal>
            <p class="eyebrow">Included</p>
            <h2 class="display-lg mt-5 text-white">With every membership.</h2>
            <p class="lead mt-5 text-white/65">No hidden joining fee and no lock-in contracts. Every plan comes with the basics done properly.</p>
        </div>
        <ul class="grid gap-4 sm:grid-cols-2 lg:col-span-7">
            @foreach([
                ['icon' => 'dumbbell', 'title' => 'Full gym floor access', 'text' => 'Use every zone during opening hours.'],
                ['icon' => 'shower', 'title' => 'Lockers & showers', 'text' => 'Included with every plan.'],
                ['icon' => 'clipboard', 'title' => 'Induction session', 'text' => 'A coached walkthrough of the floor to get you started.'],
                ['icon' => 'calendar', 'title' => 'Free freeze each quarter', 'text' => 'Pause for up to two weeks for travel or medical reasons.'],
            ] as $item)
                <li class="flex gap-4 border border-white/10 bg-ink-850 p-6" data-reveal style="--reveal-delay: {{ $loop->index * 0.06 }}s">
                    <span class="inline-flex size-12 shrink-0 items-center justify-center rounded-md bg-gold-500 text-xl text-ink-950">@icon($item['icon'], '')</span>
                    <div>
                        <p class="font-display text-2xl leading-tight font-bold text-white">{{ $item['title'] }}</p>
                        <p class="mt-1 text-white/65">{{ $item['text'] }}</p>
                    </div>
                </li>
            @endforeach
        </ul>
    </div>
</section>

<section class="bg-bone-100 py-12 lg:py-14 text-ink-950">
    <div class="wrap grid gap-10 lg:grid-cols-3 lg:items-center">
        <div data-reveal>
            <p class="eyebrow !text-gold-700">Opening hours</p>
            <h2 class="display-md mt-4">Open 7 days a week.</h2>
        </div>
        <ul class="grid gap-3 lg:col-span-1" data-reveal>
            @foreach($gymContact->hours() as $h)
                <li class="flex justify-between gap-4 border-b border-ink-950/10 pb-3 text-lg"><span class="text-ink-950/65">{{ $h['days'] }}</span><span class="font-semibold">{{ $h['time'] }}</span></li>
            @endforeach
        </ul>
        <div class="flex flex-wrap gap-3 lg:justify-end" data-reveal>
            <a href="{{ $gymContact->mapsUrl() }}" target="_blank" rel="noopener" class="btn btn-gold">@icon('pin', '') Get directions</a>
            <a href="{{ $gymContact->callUrl() }}" class="btn btn-outline-dark">@icon('phone', '') Call us</a>
        </div>
    </div>
</section>

@include('partials.site.cta-band', ['image' => 'gallery-3', 'title' => 'See it in person.', 'text' => 'Book a free trial and use the floor with a coach by your side.'])

@endsection
