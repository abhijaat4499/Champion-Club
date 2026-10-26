@extends('layouts.app')

@section('title', 'Gym Membership Plans & Prices — No Lock-in | ChampionClub ' . config('gym.address.locality'))
@section('description', 'Compare ChampionClub membership plans and prices. No joining fee, no lock-in contracts, free freeze every quarter. Book a free trial in ' . config('gym.address.locality') . '.')
@section('og_image', 'page-membership')

@php
    $faqs = [
        ['q' => 'Is there a joining fee?', 'a' => 'No hidden joining fee. The price you see on each plan is exactly what you pay, billed monthly.'],
        ['q' => 'Can I freeze my membership?', 'a' => 'Yes — every plan includes one free freeze per quarter, for up to two weeks, for travel or medical reasons.'],
        ['q' => 'Can I switch plans later?', 'a' => 'Absolutely. Upgrade or downgrade any time from the front desk — the change applies from your next billing cycle.'],
        ['q' => 'What if I want to cancel?', 'a' => "There's no lock-in contract on any plan. Cancel any time, no cancellation fee."],
        ['q' => 'Do you offer student or corporate discounts?', 'a' => 'Yes — ask the front desk about current student and corporate group offers.'],
    ];
@endphp

@push('schema')
<script type="application/ld+json">{!! json_encode([
    '@context' => 'https://schema.org',
    '@type' => 'FAQPage',
    'mainEntity' => collect($faqs)->map(fn ($f) => ['@type' => 'Question', 'name' => $f['q'], 'acceptedAnswer' => ['@type' => 'Answer', 'text' => $f['a']]])->all(),
], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}</script>
@endpush

@section('content')

<x-site.page-hero image="page-membership" eyebrow="Membership" title="Membership that matches your intensity." crumb="Membership">
    No lock-in contracts. Pause or cancel any time. Pick the plan that fits where you are right now — you can always change it later.
    <x-slot:actions>
        <a href="#plans" class="btn btn-gold">See plans @icon('down', '')</a>
        <a href="{{ route('gym.trial') }}" class="btn btn-ghost">Try it free first</a>
    </x-slot:actions>
</x-site.page-hero>

{{-- ---------- Plans ---------- --}}
<section id="plans" class="scroll-mt-24 overflow-hidden bg-ink-950 pt-10 pb-14 lg:pt-12 lg:pb-16">
    <div class="wrap">
        @if($plans->isNotEmpty())
            @php $featuredIndex = max(0, $plans->search(fn ($p) => $p->is_featured)); @endphp
            <div data-carousel="plans" data-initial="{{ $featuredIndex }}">
                <div class="plans-slider swiper !overflow-visible !pt-6" style="--initial: {{ $featuredIndex }}" data-reveal>
                    <div class="swiper-wrapper">
                        @foreach($plans as $plan)
                            <div class="swiper-slide !h-auto px-2 lg:px-0 {{ $loop->index === $featuredIndex ? 'swiper-slide-active' : '' }}">
                                @include('partials.site.plan-card', ['plan' => $plan])
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="carousel-dots mt-10 justify-center text-white lg:hidden" data-dots></div>
            </div>
        @else
            <p class="text-center text-white/60">Plans will be published soon. <a href="{{ route('gym.contact', ['interest' => 'membership']) }}" class="text-gold-400 underline">Ask us for current prices.</a></p>
        @endif

        <div class="mt-16 grid gap-px overflow-hidden rounded-sm border border-white/10 bg-white/10 sm:grid-cols-3" data-reveal>
            @foreach([
                ['icon' => 'dumbbell', 'title' => 'Full gym floor access', 'text' => 'Included with every plan.'],
                ['icon' => 'shower', 'title' => 'Lockers & showers', 'text' => 'Included with every plan.'],
                ['icon' => 'clipboard', 'title' => 'Induction session', 'text' => 'One coached induction to get you started.'],
            ] as $item)
                <div class="flex items-center gap-4 bg-ink-900 p-6">
                    <span class="icon-ring">@icon($item['icon'], 'text-lg')</span>
                    <div>
                        <p class="font-bold text-white">{{ $item['title'] }}</p>
                        <p class="text-sm text-white/55">{{ $item['text'] }}</p>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-12 flex flex-col items-center gap-5 sm:flex-row sm:justify-center" data-reveal>
            <a href="{{ $gymContact->whatsappUrl() }}" target="_blank" rel="noopener" class="btn btn-whatsapp">@icon('whatsapp', '') Get your membership</a>
            <a href="{{ $gymContact->callUrl() }}" class="btn btn-ghost">@icon('phone', '') Call {{ $gymContact->displayPhone() }}</a>
        </div>
    </div>
</section>

{{-- ---------- FAQs ---------- --}}
<section class="bg-bone-100 pt-12 pb-14 text-ink-950 lg:pt-14 lg:pb-16">
    <div class="wrap grid gap-12 lg:grid-cols-12">
        <div class="lg:col-span-5" data-reveal>
            <p class="eyebrow !text-gold-700">FAQs</p>
            <h2 class="display-lg mt-5">Membership questions, answered.</h2>
            <p class="lead mt-5 text-ink-950/65">Still deciding? Here's what members ask most before signing up.</p>
            <a href="{{ $gymContact->whatsappUrl('Hi, I have a question about membership') }}" target="_blank" rel="noopener" class="btn btn-dark mt-8">@icon('whatsapp', '') Ask us anything</a>
        </div>
        <div class="lg:col-span-7">
            <div class="divide-y divide-ink-950/10 border-y border-ink-950/10">
                @foreach($faqs as $faq)
                    <details class="accordion group py-2" data-reveal @if($loop->first) open @endif>
                        <summary class="py-5 font-display text-2xl leading-tight font-bold">
                            {{ $faq['q'] }}
                            <span class="inline-flex size-10 shrink-0 items-center justify-center rounded-full border border-ink-950/15 text-lg transition group-open:border-ink-950 group-open:bg-ink-950 group-open:text-gold-400">@icon('down', '')</span>
                        </summary>
                        <p class="accordion-body max-w-2xl pb-6 text-[1.02rem] leading-relaxed text-ink-950/70">{{ $faq['a'] }}</p>
                    </details>
                @endforeach
            </div>
        </div>
    </div>
</section>

@include('partials.site.cta-band', ['image' => 'gallery-4', 'title' => 'Still not sure which plan fits?', 'text' => "Walk in, meet the coaches, and we'll help you pick — no pressure, no sales pitch."])

@endsection
