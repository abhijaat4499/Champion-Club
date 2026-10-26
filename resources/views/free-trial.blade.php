@extends('layouts.app')

@section('title', 'Book a Free Trial Session | ChampionClub Gym ' . config('gym.address.locality'))
@section('description', 'Book a free trial session at ChampionClub ' . config('gym.address.locality') . '. Meet a coach, use the training floor and find the right program — no pressure, no sales pitch.')
@section('og_image', 'page-trial')

@section('content')

<section class="relative isolate overflow-hidden bg-ink-950 pt-32 pb-14 lg:pt-44 lg:pb-16">
    @img('page-trial', 'absolute inset-0 -z-20 size-full object-cover opacity-45', true)
    <div class="absolute inset-0 -z-10 bg-gradient-to-r from-ink-950 via-ink-950/85 to-ink-950/50"></div>

    <div class="wrap grid items-start gap-12 lg:grid-cols-12">
        <div class="lg:col-span-6 lg:pt-6">
            <nav aria-label="Breadcrumb" class="mb-6 text-xs font-semibold tracking-[0.18em] text-white/55 uppercase">
                <ol class="flex items-center gap-2">
                    <li><a href="{{ url('/') }}" class="transition hover:text-gold-400">Home</a></li>
                    <li aria-hidden="true">/</li>
                    <li aria-current="page" class="text-gold-400">Free trial</li>
                </ol>
            </nav>
            <p class="eyebrow">Free trial</p>
            <h1 class="display-xl mt-5 text-white">Your first session is on us.</h1>
            <p class="lead mt-6 max-w-xl text-white/75">Walk in, meet the coaches, use the floor. No pressure, no sales pitch.</p>

            <ul class="mt-10 grid gap-4">
                @foreach([
                    ['icon' => 'users', 'text' => 'Meet a certified coach'],
                    ['icon' => 'dumbbell', 'text' => 'Train on the floor with guidance'],
                    ['icon' => 'clipboard', 'text' => 'Get help picking the right program'],
                ] as $item)
                    <li class="flex items-center gap-4 text-white">
                        <span class="icon-ring">@icon($item['icon'], 'text-lg')</span>
                        <span class="font-semibold">{{ $item['text'] }}</span>
                    </li>
                @endforeach
            </ul>

            <div class="mt-10 flex flex-wrap items-center gap-x-6 gap-y-3 text-sm text-white/70">
                <span>Prefer to talk?</span>
                <a href="{{ $gymContact->callUrl() }}" class="inline-flex items-center gap-2 font-semibold text-white hover:text-gold-400">@icon('phone', 'text-gold-500') {{ $gymContact->displayPhone() }}</a>
                <a href="{{ $gymContact->whatsappUrl('Hi, I want to book a free trial session') }}" target="_blank" rel="noopener" class="inline-flex items-center gap-2 font-semibold text-white hover:text-gold-400">@icon('whatsapp', 'text-[#25d366]') WhatsApp</a>
            </div>
        </div>

        <div class="lg:col-span-5 lg:col-start-8">
            <div class="border border-white/10 bg-ink-900/90 p-7 shadow-2xl backdrop-blur-xl sm:p-10">
                <h2 class="display-md text-white">Book your free trial</h2>
                <p class="mt-2 mb-8 text-white/60">Share your name and number — we'll call to fix a time that suits you.</p>
                @include('partials.site.lead-form', ['formId' => 'trial', 'interest' => 'trial'])
            </div>
        </div>
    </div>
</section>

<section class="bg-bone-100 pt-12 pb-14 text-ink-950 lg:pt-14 lg:pb-16">
    <div class="wrap">
        <div class="max-w-2xl" data-reveal>
            <p class="eyebrow !text-gold-700">How it works</p>
            <h2 class="display-lg mt-5">Three steps to your first session.</h2>
        </div>
        <ol class="mt-14 grid gap-6 md:grid-cols-3">
            @foreach([
                ['title' => 'Book', 'text' => 'Fill in the short form, call us, or send a WhatsApp message.'],
                ['title' => 'Meet a coach', 'text' => 'We call to fix a time and a coach meets you on the floor.'],
                ['title' => 'Train', 'text' => 'Use the equipment with guidance and find the program that fits.'],
            ] as $step)
                <li class="relative border-t-4 border-gold-500 bg-white p-8" data-reveal style="--reveal-delay: {{ $loop->index * 0.08 }}s">
                    <span class="font-display text-6xl leading-none font-extrabold text-ink-950/10">{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>
                    <h3 class="mt-4 font-display text-3xl leading-tight">{{ $step['title'] }}</h3>
                    <p class="mt-2 leading-relaxed text-ink-950/65">{{ $step['text'] }}</p>
                </li>
            @endforeach
        </ol>
    </div>
</section>

@if($services->isNotEmpty())
    <section class="overflow-hidden border-t border-ink-950/10 bg-bone-50 pt-12 pb-14 text-ink-950 lg:pt-14 lg:pb-16">
        <div class="wrap">
            <div class="flex flex-col gap-6 sm:flex-row sm:items-end sm:justify-between" data-reveal>
                <div>
                    <p class="eyebrow !text-gold-700">Try any of these</p>
                    <h2 class="display-lg mt-5">Services you can try.</h2>
                </div>
                <a href="{{ route('gym.services') }}" class="link-arrow !text-gold-700">All services @icon('arrow', '')</a>
            </div>
            <div class="mt-12 grid grid-cols-2 gap-3 sm:gap-4 lg:grid-cols-3">
                @foreach($services as $service)
                    <a href="{{ route('gym.service', $service) }}" class="zoom-media group relative block aspect-[4/3] overflow-hidden bg-ink-800" data-reveal style="--reveal-delay: {{ ($loop->index % 3) * 0.06 }}s">
                        @if($service->imageUrl())
                            <img src="{{ $service->imageUrl() }}" style="{{ $service->imageFocus() }}" alt="" width="640" height="480" loading="lazy" decoding="async" class="absolute inset-0 size-full object-cover">
                        @else
                            @img(\App\Models\Service::FALLBACK_IMAGE, 'absolute inset-0 size-full')
                        @endif
                        <div class="absolute inset-0 bg-gradient-to-t from-ink-950 via-ink-950/30 to-transparent"></div>
                        <span class="absolute inset-x-0 bottom-0 flex items-center gap-3 p-4 font-display text-xl leading-tight font-bold text-white sm:p-6 sm:text-2xl">
                            @icon($service->iconName(), 'text-gold-400') {{ $service->title }}
                        </span>
                    </a>
                @endforeach
            </div>
        </div>
    </section>
@endif

@endsection
