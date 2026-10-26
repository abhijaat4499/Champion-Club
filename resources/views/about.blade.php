@extends('layouts.app')

@section('title', 'About ChampionClub — Strength & Conditioning Gym in ' . config('gym.address.locality'))
@section('description', 'ChampionClub is a coached strength and conditioning gym in ' . config('gym.address.locality') . ', ' . config('gym.address.region') . '. Learn how we train, who we coach and what makes the floor different.')
@section('og_image', 'page-about')

@section('content')

<x-site.page-hero image="page-about" eyebrow="About us" title="A gym for people who actually train." crumb="About">
    ChampionClub is a strength and conditioning gym for people training for their next personal best — not someone else's idea of fit.
</x-site.page-hero>

{{-- ---------- Story ---------- --}}
@php $approachPhoto = \App\Support\SiteSettings::aboutImage('main'); @endphp
<section class="bg-bone-100 pt-12 pb-14 text-ink-950 lg:pt-14 lg:pb-16">
    <div class="wrap grid gap-12 lg:grid-cols-12 lg:gap-16">
        <div class="lg:col-span-7">
            <p class="eyebrow !text-gold-700" data-reveal>Our approach</p>
            <h2 class="display-lg mt-5" data-reveal>{{ config('gym.about_title') }}</h2>
            <div class="mt-7 space-y-5 text-[1.05rem] leading-relaxed text-ink-950/70" data-reveal>
                @foreach(\App\Support\SiteSettings::storyParagraphs() as $paragraph)
                    <p>{!! nl2br(e($paragraph)) !!}</p>
                @endforeach
            </div>
            <div class="mt-10 flex flex-wrap gap-3" data-reveal>
                <a href="{{ route('gym.services') }}" class="btn btn-dark">Explore services @icon('arrow', 'btn-arrow')</a>
                <a href="{{ route('gym.trial') }}" class="btn btn-outline-dark">Book a free trial</a>
            </div>
        </div>

        <div class="lg:sticky lg:top-32 lg:col-span-5 lg:self-start" data-reveal="right">
            @if($approachPhoto)
                <div class="relative pb-6 pl-6 sm:pb-8 sm:pl-8">
                    <div class="zoom-media overflow-hidden rounded-sm">
                        @include('partials.site.about-photo', ['photo' => $approachPhoto, 'class' => 'aspect-[4/5] w-full', 'alt' => 'Training at ChampionClub'])
                    </div>
                    <div class="absolute bottom-0 left-0 bg-ink-950 p-5 text-white shadow-xl sm:p-6">
                        <p class="font-display text-5xl leading-none font-extrabold text-gold-400">{{ config('gym.years_running') }}+</p>
                        <p class="mt-2 text-sm text-white/65">years coaching in {{ config('gym.address.locality') }}</p>
                    </div>
                </div>
            @else
                <div class="border-t-4 border-gold-500 bg-ink-950 p-10 text-white shadow-xl">
                    <p class="font-display text-8xl leading-none font-extrabold text-gold-400">{{ config('gym.years_running') }}+</p>
                    <p class="mt-4 text-lg text-white/70">years coaching in {{ config('gym.address.locality') }}</p>
                </div>
            @endif
        </div>
    </div>
</section>

{{-- ---------- Highlights ---------- --}}
<section aria-label="ChampionClub at a glance" class="bg-ink-950 py-12 lg:py-14">
    <div class="wrap grid grid-cols-2 gap-y-10 lg:grid-cols-4">
        @foreach(config('gym.highlights') as $h)
            <div class="border-l border-white/10 pl-6" data-reveal style="--reveal-delay: {{ $loop->index * 0.08 }}s">
                @icon($h['icon'], 'text-2xl text-gold-500')
                <p class="mt-4 font-display text-5xl leading-none font-extrabold text-white"><span class="inline-block tabular-nums" data-count="{{ $h['value'] }}">{{ number_format($h['value']) }}</span>{{ $h['suffix'] }}</p>
                <p class="mt-2 text-sm text-white/60">{{ $h['label'] }}</p>
            </div>
        @endforeach
    </div>
</section>

{{-- ---------- Services ---------- --}}
@if($services->isNotEmpty())
<section class="bg-bone-100 pt-12 pb-14 text-ink-950 lg:pt-14 lg:pb-16">
    <div class="wrap">
        <div class="flex flex-col gap-6 sm:flex-row sm:items-end sm:justify-between" data-reveal>
            <div class="max-w-2xl">
                <p class="eyebrow !text-gold-700">What we offer</p>
                <h2 class="display-lg mt-5">Coached services for every goal.</h2>
            </div>
            <a href="{{ route('gym.services') }}" class="link-arrow !text-gold-700">All services @icon('arrow', '')</a>
        </div>
        <div class="mt-12 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
            @foreach($services as $service)
                <div data-reveal style="--reveal-delay: {{ ($loop->index % 3) * 0.08 }}s">
                    @include('partials.site.service-card', ['service' => $service])
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- ---------- Coaches preview ---------- --}}
@if($trainers->isNotEmpty())
<section class="border-t border-ink-950/10 bg-bone-50 pt-12 pb-14 text-ink-950 lg:pt-14 lg:pb-16">
    <div class="wrap">
        <div class="flex flex-col gap-6 sm:flex-row sm:items-end sm:justify-between" data-reveal>
            <div>
                <p class="eyebrow !text-gold-700">The team</p>
                <h2 class="display-lg mt-5">Meet some of our coaches.</h2>
            </div>
            <a href="{{ route('gym.trainers') }}" class="link-arrow !text-gold-700">All coaches @icon('arrow', '')</a>
        </div>
        <div class="mt-12 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
            @foreach($trainers as $trainer)
                <div data-reveal style="--reveal-delay: {{ $loop->index * 0.08 }}s">
                    @include('partials.site.trainer-card', ['trainer' => $trainer, 'bioLimit' => 110])
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- ---------- About sections ---------- --}}
@foreach($aboutSections as $section)
    @php
        $dark = $loop->index % 2 === 0;
        $flip = $loop->index % 2 === 1;
        $blocks = $section->bodyBlocks();
    @endphp
    <section class="{{ $dark ? 'bg-ink-950 text-white' : 'border-t border-ink-950/10 bg-bone-100 text-ink-950' }} pt-12 pb-14 lg:pt-14 lg:pb-16">
        <div class="wrap">
            @if($section->imageUrl())
                <div class="grid gap-10 lg:grid-cols-12 lg:gap-16">
                    <div class="lg:sticky lg:top-32 lg:col-span-5 lg:self-start {{ $flip ? 'lg:order-2' : '' }}" data-reveal="{{ $flip ? 'right' : 'left' }}">
                        <div class="zoom-media overflow-hidden rounded-sm">
                            <img src="{{ $section->imageUrl() }}" style="{{ $section->imageFocus() }}" alt="{{ $section->title }}" width="1200" height="1500" loading="lazy" decoding="async" class="aspect-[4/3] w-full object-cover lg:aspect-[4/5]">
                        </div>
                    </div>
                    <div class="lg:col-span-7">
                        @if($section->eyebrow)
                            <p class="eyebrow {{ $dark ? '' : '!text-gold-700' }}" data-reveal>{{ $section->eyebrow }}</p>
                        @endif
                        <h2 class="display-lg {{ $section->eyebrow ? 'mt-5' : '' }}" data-reveal>{{ $section->title }}</h2>
                        <div class="service-copy {{ $dark ? 'service-copy-dark' : '' }} mt-8" data-reveal>
                            @foreach($blocks as $block)
                                @if($block['type'] === 'heading')
                                    <h3>{{ $block['text'] }}</h3>
                                @else
                                    <p>{{ $block['text'] }}</p>
                                @endif
                            @endforeach
                        </div>
                    </div>
                </div>
            @else
                <div class="grid gap-8 lg:grid-cols-12 lg:gap-16">
                    <div class="lg:sticky lg:top-32 lg:col-span-5 lg:self-start" data-reveal>
                        @if($section->eyebrow)
                            <p class="eyebrow {{ $dark ? '' : '!text-gold-700' }}">{{ $section->eyebrow }}</p>
                        @endif
                        <h2 class="display-lg {{ $section->eyebrow ? 'mt-5' : '' }}">{{ $section->title }}</h2>
                        <span class="mt-8 block h-1 w-16 bg-gold-500" aria-hidden="true"></span>
                    </div>
                    <div class="service-copy {{ $dark ? 'service-copy-dark' : '' }} lg:col-span-7 lg:pt-2" data-reveal>
                        @foreach($blocks as $block)
                            @if($block['type'] === 'heading')
                                <h3>{{ $block['text'] }}</h3>
                            @else
                                <p>{{ $block['text'] }}</p>
                            @endif
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </section>
@endforeach

@include('partials.site.cta-band', ['image' => 'gallery-8', 'title' => 'Come see the floor for yourself.', 'text' => 'Walk in, meet the coaches, use the floor. Your first session is on us.'])

@endsection
