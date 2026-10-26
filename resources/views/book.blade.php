@extends('layouts.app')

@php
    $card = $booking['card'] ?? null;
    $coach = $booking['trainer'] ?? null;
    $current = $card['name'] ?? $coach['name'];
    $titleClass = mb_strlen($booking['title']) > 26 ? 'display-lg' : 'display-xl';
@endphp

@section('title', $booking['pageTitle'] . ' | ' . config('gym.name') . ' ' . config('gym.address.locality'))
@section('description', $booking['lead'])
@section('og_image', $booking['image'])

@section('content')

<section class="relative isolate overflow-hidden bg-ink-950 pt-32 pb-14 lg:pt-44 lg:pb-16">
    @if(! empty($booking['bgUrl']))
        <img src="{{ $booking['bgUrl'] }}" style="{{ $booking['bgFocus'] ?? '' }}" alt="" width="1600" height="1000" fetchpriority="high" decoding="async" class="absolute inset-0 -z-20 size-full object-cover opacity-40">
    @else
        @img($booking['image'], 'absolute inset-0 -z-20 size-full object-cover opacity-40', true)
    @endif
    <div class="absolute inset-0 -z-10 bg-gradient-to-r from-ink-950 via-ink-950/85 to-ink-950/50"></div>

    <div class="wrap grid items-start gap-12 lg:grid-cols-12">
        <div class="lg:col-span-6 lg:pt-6">
            <nav aria-label="Breadcrumb" class="mb-6 text-xs font-semibold tracking-[0.18em] text-white/55 uppercase">
                <ol class="flex flex-wrap items-center gap-2">
                    <li><a href="{{ url('/') }}" class="transition hover:text-gold-400">Home</a></li>
                    <li aria-hidden="true">/</li>
                    <li><a href="{{ $booking['crumb']['url'] }}" class="transition hover:text-gold-400">{{ $booking['crumb']['label'] }}</a></li>
                    <li aria-hidden="true">/</li>
                    <li aria-current="page" class="text-gold-400">{{ $current }}</li>
                </ol>
            </nav>
            <p class="eyebrow">{{ $booking['eyebrow'] }}</p>
            <h1 class="{{ $titleClass }} mt-5 text-white">{{ $booking['title'] }}</h1>
            <p class="lead mt-6 max-w-xl text-white/75">{{ $booking['lead'] }}</p>

            {{-- ---------- Selected item ---------- --}}
            @if($card)
                <div class="mt-10 max-w-xl rounded-md border border-gold-500/50 bg-gradient-to-b from-gold-500/[0.14] via-ink-900/90 to-ink-900/90 p-7 backdrop-blur-md sm:p-8">
                    <div class="flex flex-wrap items-center justify-between gap-3">
                        <span class="rounded-full bg-gold-500 px-3.5 py-1 text-[0.7rem] font-extrabold tracking-[0.14em] text-ink-950 uppercase">{{ $card['label'] }}</span>
                        @if(! empty($card['discount']))
                            <span class="rounded-full border border-gold-500/60 px-3 py-1 text-xs font-bold text-gold-400">{{ $card['discount'] }}% off</span>
                        @endif
                    </div>
                    <h2 class="mt-5 font-display text-[2rem] leading-none text-white">{{ $card['name'] }}</h2>
                    @if(! empty($card['summary']))
                        <p class="mt-4 leading-relaxed text-white/70">{{ $card['summary'] }}</p>
                    @endif
                    @if($card['price'])
                        <p class="mt-5 flex flex-wrap items-baseline gap-x-3 gap-y-1">
                            <span class="font-display text-6xl leading-none font-extrabold text-white">{{ $card['price'] }}</span>
                            @if($card['period'])
                                <span class="text-sm font-semibold text-white/50">{{ $card['period'] }}</span>
                            @endif
                            @if($card['original'])
                                <s class="text-lg font-semibold text-white/40">{{ $card['original'] }}</s>
                            @endif
                        </p>
                    @endif
                    @if($card['points'])
                        <ul class="mt-6 space-y-3 border-t border-white/10 pt-6 text-[0.95rem]">
                            @foreach($card['points'] as $point)
                                <li class="flex gap-3 text-white/80">
                                    @icon('check', 'mt-1 shrink-0 text-sm text-gold-400')
                                    <span>{{ $point }}</span>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                    @if($card['note'])
                        <p class="mt-6 flex items-center gap-2 text-sm font-semibold text-gold-400">
                            @icon($booking['type'] === 'offer' ? 'clock' : 'check', '') {{ $card['note'] }}
                        </p>
                    @endif
                    @if(! empty($card['url']))
                        <a href="{{ $card['url'] }}" class="link-arrow mt-6">About this service @icon('arrow', '')</a>
                    @endif
                </div>
            @elseif($coach)
                <div class="mt-10 flex max-w-xl gap-5 rounded-md border border-gold-500/50 bg-ink-900/90 p-5 backdrop-blur-md sm:gap-6 sm:p-6">
                    <div class="aspect-[4/5] w-28 shrink-0 overflow-hidden rounded-sm bg-ink-800 sm:w-36">
                        @if($coach['photo'])
                            <img src="{{ $coach['photo'] }}" alt="{{ $coach['name'] }}, {{ $coach['role'] }}" width="144" height="180" class="size-full object-cover object-[50%_20%]">
                        @else
                            <div class="flex size-full items-center justify-center bg-[radial-gradient(circle_at_50%_40%,rgba(212,165,55,0.28),transparent_62%)]">
                                <span class="bg-gradient-to-b from-gold-200 to-gold-600 bg-clip-text font-display text-5xl leading-none font-extrabold text-transparent">{{ $coach['initials'] }}</span>
                            </div>
                        @endif
                    </div>
                    <div class="min-w-0">
                        <span class="inline-block rounded-full bg-gold-500 px-3 py-1 text-[0.65rem] font-extrabold tracking-[0.12em] text-ink-950 uppercase">{{ $coach['role'] }}</span>
                        <h2 class="mt-3 font-display text-[1.85rem] leading-none text-white">{{ $coach['name'] }}</h2>
                        <p class="mt-3 text-[0.95rem] leading-relaxed text-white/65">{{ \Illuminate\Support\Str::limit($coach['bio'], 260) }}</p>
                    </div>
                </div>
            @endif

            <div class="mt-8 flex flex-wrap items-center gap-x-6 gap-y-3 text-sm text-white/70">
                <span>Prefer to talk?</span>
                <a href="{{ $gymContact->callUrl() }}" class="inline-flex items-center gap-2 font-semibold text-white hover:text-gold-400">@icon('phone', 'text-gold-500') {{ $gymContact->displayPhone() }}</a>
                <a href="{{ $gymContact->whatsappUrl($booking['whatsapp']) }}" target="_blank" rel="noopener" class="inline-flex items-center gap-2 font-semibold text-white hover:text-gold-400">@icon('whatsapp', 'text-[#25d366]') WhatsApp</a>
            </div>
        </div>

        <div class="lg:col-span-5 lg:col-start-8">
            <div class="border border-white/10 bg-ink-900/90 p-7 shadow-2xl backdrop-blur-xl sm:p-10">
                <h2 class="display-md text-white">{{ $booking['formTitle'] }}</h2>
                <p class="mt-2 mb-8 text-white/60">{{ $booking['formText'] }}</p>
                @include('partials.site.lead-form', ['formId' => 'book', 'booking' => $booking, 'button' => $booking['button']])
            </div>
        </div>
    </div>
</section>

<section class="bg-bone-100 pt-12 pb-14 text-ink-950 lg:pt-14 lg:pb-16">
    <div class="wrap">
        <div class="max-w-2xl" data-reveal>
            <p class="eyebrow !text-gold-700">How it works</p>
            <h2 class="display-lg mt-5">Three steps to get started.</h2>
        </div>
        <ol class="mt-14 grid gap-6 md:grid-cols-3">
            @foreach($booking['steps'] as $step)
                <li class="relative border-t-4 border-gold-500 bg-white p-8" data-reveal style="--reveal-delay: {{ $loop->index * 0.08 }}s">
                    <span class="font-display text-6xl leading-none font-extrabold text-ink-950/10">{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>
                    <h3 class="mt-4 font-display text-3xl leading-tight">{{ $step['title'] }}</h3>
                    <p class="mt-2 leading-relaxed text-ink-950/65">{{ $step['text'] }}</p>
                </li>
            @endforeach
        </ol>
    </div>
</section>

@endsection
