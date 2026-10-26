@extends('layouts.app')

@php
    $highlights = $service->highlightList();
    $blocks = $service->descriptionBlocks();
    $bookUrl = route('gym.book.service', $service);
@endphp

@section('title', $service->title . ' | ' . config('gym.name') . ' ' . config('gym.address.locality'))
@section('description', $service->summary)
@section('og_image', \App\Models\Service::FALLBACK_IMAGE)

@section('content')

{{-- ---------- Hero ---------- --}}
<section class="relative isolate flex min-h-[62svh] items-end overflow-hidden bg-ink-950 pt-36 pb-14 sm:min-h-[66svh] lg:min-h-[72vh] lg:pb-20">
    @if($service->imageUrl())
        <img src="{{ $service->imageUrl() }}" style="{{ $service->heroFocus() }}" alt="" width="1600" height="1000" fetchpriority="high" decoding="async" class="absolute inset-0 -z-20 size-full object-cover animate-ken-burns">
    @else
        @img(\App\Models\Service::FALLBACK_IMAGE, 'absolute inset-0 -z-20 size-full object-cover animate-ken-burns', true)
    @endif
    <div class="absolute inset-0 -z-10 bg-gradient-to-t from-ink-950 via-ink-950/70 to-ink-950/35"></div>
    <div class="absolute inset-y-0 left-0 -z-10 w-2/3 bg-gradient-to-r from-ink-950/80 to-transparent"></div>

    <div class="wrap">
        <div class="max-w-3xl">
            <nav aria-label="Breadcrumb" class="mb-6 text-xs font-semibold tracking-[0.18em] text-white/55 uppercase">
                <ol class="flex flex-wrap items-center gap-2">
                    <li><a href="{{ url('/') }}" class="transition hover:text-gold-400">Home</a></li>
                    <li aria-hidden="true">/</li>
                    <li><a href="{{ route('gym.services') }}" class="transition hover:text-gold-400">Services</a></li>
                    <li aria-hidden="true">/</li>
                    <li aria-current="page" class="text-gold-400">{{ $service->title }}</li>
                </ol>
            </nav>
            <div class="flex items-center gap-4">
                <span class="icon-tile">@icon($service->iconName(), '')</span>
                <p class="eyebrow">Service</p>
            </div>
            <h1 class="{{ mb_strlen($service->title) > 28 ? 'display-lg' : 'display-xl' }} mt-6 text-white">{{ $service->title }}</h1>
            <p class="lead mt-6 max-w-2xl text-white/75">{{ $service->summary }}</p>

            @if($highlights)
                <ul class="mt-8 flex flex-wrap gap-2.5 text-[0.85rem] font-semibold text-white">
                    @foreach($highlights as $highlight)
                        <li class="inline-flex items-center gap-2 rounded-full border border-white/20 bg-white/5 px-4 py-2 backdrop-blur">
                            @icon('check', 'text-gold-400') {{ $highlight }}
                        </li>
                    @endforeach
                </ul>
            @endif

            <div class="mt-9 flex flex-wrap gap-3">
                <a href="{{ $bookUrl }}" class="btn btn-gold">Book {{ $service->title }} @icon('arrow', 'btn-arrow')</a>
                <a href="{{ $gymContact->whatsappUrl($booking['whatsapp']) }}" target="_blank" rel="noopener" class="btn btn-ghost">@icon('whatsapp', '') Ask on WhatsApp</a>
            </div>
        </div>
    </div>
</section>

{{-- ---------- About this service ---------- --}}
<section class="bg-bone-100 pt-12 pb-14 text-ink-950 lg:pt-14 lg:pb-16">
    <div class="wrap">
        <div class="grid gap-12 lg:grid-cols-12 lg:gap-16">
            <div class="lg:sticky lg:top-32 lg:col-span-5 lg:self-start" data-reveal="left">
                <div class="zoom-media relative overflow-hidden rounded-sm">
                    @if($service->imageUrl())
                        <img src="{{ $service->imageUrl() }}" style="{{ $service->imageFocus() }}" alt="{{ $service->title }}" width="800" height="1000" loading="lazy" decoding="async" class="aspect-[4/3] w-full object-cover lg:aspect-[4/5]">
                    @else
                        @img(\App\Models\Service::FALLBACK_IMAGE, 'aspect-[4/3] w-full lg:aspect-[4/5]', false, $service->title)
                    @endif
                </div>
            </div>

            <div class="lg:col-span-7">
                <p class="eyebrow !text-gold-700" data-reveal>About this service</p>
                <h2 class="display-lg mt-5" data-reveal>What to expect.</h2>
                <div class="service-copy mt-8" data-reveal>
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

        <div class="mt-16 flex flex-col gap-6 rounded-md bg-ink-950 p-8 text-white sm:p-10 lg:flex-row lg:items-center lg:justify-between" data-reveal>
            <div>
                <p class="eyebrow">Ready to start?</p>
                <h2 class="display-md mt-4">Book {{ $service->title }} today.</h2>
                <p class="mt-3 max-w-xl text-white/65">Share your details and our team will call to fix a time that suits you.</p>
            </div>
            <div class="flex shrink-0 flex-wrap gap-3">
                <a href="{{ $bookUrl }}" class="btn btn-gold">Book {{ $service->title }} @icon('arrow', 'btn-arrow')</a>
                <a href="{{ $gymContact->callUrl() }}" class="btn btn-ghost">@icon('phone', '') Call us</a>
            </div>
        </div>
    </div>
</section>

@if($others->isNotEmpty())
    <section class="border-t border-ink-950/10 bg-bone-50 pt-12 pb-14 text-ink-950 lg:pt-14 lg:pb-16">
        <div class="wrap">
            <div class="flex flex-col gap-6 sm:flex-row sm:items-end sm:justify-between" data-reveal>
                <div>
                    <p class="eyebrow !text-gold-700">More services</p>
                    <h2 class="display-lg mt-5">Mix it up.</h2>
                </div>
                <a href="{{ route('gym.services') }}" class="link-arrow !text-gold-700">All services @icon('arrow', '')</a>
            </div>
            <div class="mt-12 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                @foreach($others as $other)
                    <div data-reveal style="--reveal-delay: {{ $loop->index * 0.08 }}s">
                        @include('partials.site.service-card', ['service' => $other])
                    </div>
                @endforeach
            </div>
        </div>
    </section>
@endif

@endsection
