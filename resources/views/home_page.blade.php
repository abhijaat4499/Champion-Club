@extends('layouts.app')

@section('title', 'ChampionClub — Strength & Conditioning Gym in ' . config('gym.address.locality'))
@section('description', 'Coached strength training, functional training, HIIT and cardio at ChampionClub, ' . config('gym.address.locality') . '. Certified coaches, competition-grade equipment, no lock-in contracts. Book a free trial.')
@section('body_class', 'page-home')

@push('preload')
    @php $firstSlide = $heroSlides->first(); @endphp
    @if($firstSlide?->image)
        <link rel="preload" as="image" href="{{ asset('storage/' . $firstSlide->image) }}" media="(min-width: 641px)" fetchpriority="high">
        <link rel="preload" as="image" href="{{ asset('storage/' . ($firstSlide->image_mobile ?: $firstSlide->image)) }}" media="(max-width: 640px)" fetchpriority="high">
    @elseif($firstSlide?->site_image)
        <link rel="preload" as="image" href="{{ asset(config('site-images.' . $firstSlide->site_image . '.src')) }}" media="(min-width: 641px)" fetchpriority="high">
        <link rel="preload" as="image" href="{{ asset(config('site-images.' . $firstSlide->site_image . '.sm')) }}" media="(max-width: 640px)" fetchpriority="high">
    @endif
@endpush

@section('content')

{{-- ---------- Hero slider ---------- --}}
@include('partials.site.hero-slider')

{{-- ---------- Highlights ---------- --}}
<section aria-label="ChampionClub at a glance" class="border-b border-white/10 bg-ink-900">
    <div class="wrap grid grid-cols-2 lg:grid-cols-4">
        @foreach(config('gym.highlights') as $h)
            <div class="flex flex-col gap-4 border-white/10 px-2 py-9 sm:flex-row sm:items-center sm:px-6 lg:py-11 {{ $loop->odd ? 'border-r' : '' }} {{ $loop->index < 2 ? 'max-lg:border-b' : '' }} lg:border-r lg:last:border-r-0" data-reveal style="--reveal-delay: {{ $loop->index * 0.08 }}s">
                <span class="icon-tile">@icon($h['icon'], '')</span>
                <div>
                    <p class="font-display text-4xl leading-none font-extrabold text-white lg:text-5xl"><span class="inline-block tabular-nums" data-count="{{ $h['value'] }}">{{ number_format($h['value']) }}</span>{{ $h['suffix'] }}</p>
                    <p class="mt-1.5 text-sm font-medium text-white/60">{{ $h['label'] }}</p>
                </div>
            </div>
        @endforeach
    </div>
</section>

{{-- ---------- Gallery ---------- --}}
@if($galleryPhotos->isNotEmpty())
<section id="our-gym" class="bg-bone-50 pt-12 pb-14 text-ink-950 lg:pt-14 lg:pb-16">
    <div class="wrap">
        <div class="flex flex-col gap-6 sm:flex-row sm:items-end sm:justify-between" data-reveal>
            <div>
                <p class="eyebrow !text-gold-700">Take a look inside</p>
                <h2 class="display-lg mt-5">Our gym</h2>
            </div>
            <a href="{{ route('gym.gallery') }}" class="link-arrow !text-gold-700 max-sm:hidden">View full gallery @icon('arrow', '')</a>
        </div>

        <div class="home-gallery mt-12">
            @foreach($galleryPhotos as $photo)
                <a href="{{ route('gym.gallery') }}" class="home-gallery-item zoom-media group" data-reveal="zoom" style="--reveal-delay: {{ $loop->index * 0.06 }}s">
                    <img src="{{ $photo->url() }}" alt="{{ $photo->alt }}" width="{{ $photo->width ?: 1400 }}" height="{{ $photo->height ?: 933 }}" loading="lazy" decoding="async" class="absolute inset-0 size-full object-cover">
                    <span class="absolute inset-0 bg-gradient-to-t from-ink-950/75 via-transparent to-transparent"></span>
                    @if($photo->caption)
                        <span class="absolute inset-x-0 bottom-0 p-4 font-display text-lg font-bold text-white sm:p-5 sm:text-xl">{{ $photo->caption }}</span>
                    @endif
                </a>
            @endforeach
        </div>

        <div class="mt-10 flex justify-center" data-reveal>
            <a href="{{ route('gym.gallery') }}" class="btn btn-dark">View full gallery @icon('arrow', 'btn-arrow')</a>
        </div>
    </div>
</section>
@endif

{{-- ---------- About ---------- --}}
<section class="overflow-hidden border-t border-ink-950/10 bg-bone-100 pt-12 pb-14 text-ink-950 lg:pt-14 lg:pb-16">
    <div class="wrap grid items-center gap-16 lg:grid-cols-12 lg:gap-12">
        <div class="relative lg:col-span-6" data-reveal="left">
            <div class="relative w-[80%]">
                <div class="absolute -top-5 -left-5 h-full w-full border-2 border-gold-500"></div>
                @img('about-main', 'relative aspect-[3/4] w-full')
            </div>
            <div class="absolute right-0 -bottom-10 w-[46%] border-[10px] border-bone-100 shadow-2xl" data-reveal="mask">
                @img('about-small', 'aspect-square w-full')
            </div>
            <div class="absolute top-10 right-4 flex size-32 flex-col items-center justify-center rounded-full bg-ink-950 text-center text-white shadow-xl sm:right-8 sm:size-36">
                <span class="font-display text-5xl leading-none font-extrabold text-gold-400">{{ config('gym.years_running') }}+</span>
                <span class="mt-1 text-[0.7rem] font-bold tracking-[0.16em] uppercase">Years<br>running</span>
            </div>
        </div>

        <div class="lg:col-span-6 lg:pl-8">
            <p class="eyebrow !text-gold-700" data-reveal>About ChampionClub</p>
            <h2 class="display-lg mt-5" data-reveal>No mirror selfies.<br>No wasted sets.</h2>
            <p class="lead mt-6 text-ink-950/70" data-reveal>ChampionClub is built around three things most gyms only claim to offer. Every program here is coached, not just supervised — whether it's your first squat or your next meet.</p>

            <ul class="mt-10 grid gap-7">
                @foreach([
                    ['icon' => 'medal', 'title' => 'Coaches who coach', 'text' => 'Every session is supervised by a certified trainer who corrects your form, not just your rep count.'],
                    ['icon' => 'dumbbell', 'title' => 'Equipment built for serious training', 'text' => 'Competition-grade barbells and platforms, maintained on a weekly schedule.'],
                    ['icon' => 'users', 'title' => 'A community that pushes you', 'text' => 'Train alongside people who show up at 6 AM because they want to, not because an app reminded them.'],
                ] as $p)
                    <li class="flex gap-5" data-reveal style="--reveal-delay: {{ $loop->index * 0.1 }}s">
                        <span class="inline-flex size-12 shrink-0 items-center justify-center rounded-md bg-ink-950 text-xl text-gold-400">@icon($p['icon'], '')</span>
                        <div>
                            <h3 class="font-display text-2xl leading-tight">{{ $p['title'] }}</h3>
                            <p class="mt-1.5 leading-relaxed text-ink-950/65">{{ $p['text'] }}</p>
                        </div>
                    </li>
                @endforeach
            </ul>

            <div class="mt-11 flex flex-wrap items-center gap-6" data-reveal>
                <a href="{{ route('gym.about') }}" class="btn btn-dark">Our story @icon('arrow', 'btn-arrow')</a>
                <a href="{{ route('gym.trainers') }}" class="inline-flex items-center gap-2 font-bold text-ink-950 underline decoration-gold-500 decoration-2 underline-offset-8 transition hover:text-gold-700">Meet the coaches</a>
            </div>
        </div>
    </div>
</section>

{{-- ---------- Facilities ---------- --}}
<section class="border-t border-ink-950/10 bg-bone-50 pt-12 pb-14 text-ink-950 lg:pt-14 lg:pb-16">
    <div class="wrap">
        <div class="grid gap-8 lg:grid-cols-12 lg:items-end">
            <div class="lg:col-span-7" data-reveal>
                <p class="eyebrow !text-gold-700">Facilities</p>
                <h2 class="display-lg mt-5">20,000 sq ft. Zero filler.</h2>
            </div>
            <div class="lg:col-span-5" data-reveal style="--reveal-delay: .1s">
                <p class="text-ink-950/70">Platforms, free weights, a functional zone and a cardio zone — with lockers and showers included in every plan.</p>
            </div>
        </div>

        @if($facilities->isNotEmpty())
            <div class="mt-14 grid grid-cols-2 gap-3 sm:gap-4 {{ $facilities->count() === 5 ? 'lg:h-[660px] lg:grid-cols-4 lg:grid-rows-2' : 'lg:grid-cols-3' }}">
                @foreach($facilities as $facility)
                    @php $feature = $loop->first && $loop->count === 5; @endphp
                    <figure class="zoom-media group relative overflow-hidden rounded-sm bg-ink-900 {{ $feature ? 'col-span-2 aspect-[4/3] lg:row-span-2 lg:aspect-auto' : ($loop->count === 5 ? 'aspect-square lg:aspect-auto' : 'aspect-square') }}" data-reveal="zoom" style="--reveal-delay: {{ $loop->index * 0.08 }}s">
                        @include('partials.site.facility-image', ['facility' => $facility, 'class' => 'absolute inset-0 size-full'])
                        <div class="absolute inset-0 bg-gradient-to-t from-ink-950/85 via-transparent to-transparent"></div>
                        <figcaption class="absolute inset-x-0 bottom-0 p-4 sm:p-6">
                            <span class="block font-display text-xl leading-tight font-bold text-white sm:text-2xl {{ $feature ? 'lg:text-4xl' : '' }}">{{ $facility->title }}</span>
                            @if($feature)
                                <span class="mt-2 hidden max-w-sm text-sm text-white/75 sm:block">{{ $facility->text }}</span>
                            @endif
                        </figcaption>
                    </figure>
                @endforeach
            </div>
        @endif

        <div class="mt-10 flex justify-center" data-reveal>
            <a href="{{ route('gym.facilities') }}" class="btn btn-dark">Explore the facilities @icon('arrow', 'btn-arrow')</a>
        </div>
    </div>
</section>

{{-- ---------- Trainers ---------- --}}
@if($trainers->isNotEmpty())
<section class="bg-ink-900 pt-12 pb-14 lg:pt-14 lg:pb-16" data-carousel="trainers">
    <div class="wrap">
        <div class="flex flex-col gap-8 lg:flex-row lg:items-end lg:justify-between">
            <div data-reveal>
                <p class="eyebrow">Coaches</p>
                <h2 class="display-lg mt-5 text-white">Coaches who've<br>done the work.</h2>
            </div>
            <div class="flex items-center gap-3" data-reveal>
                <a href="{{ route('gym.trainers') }}" class="link-arrow mr-3">All coaches @icon('arrow', '')</a>
                <button type="button" class="carousel-btn carousel-btn-dark" data-prev aria-label="Previous coaches">@icon('arrow', 'rotate-180')</button>
                <button type="button" class="carousel-btn carousel-btn-dark" data-next aria-label="Next coaches">@icon('arrow', '')</button>
            </div>
        </div>

        <div class="swiper mt-14" data-reveal>
            <div class="swiper-wrapper">
                @foreach($trainers as $trainer)
                    <div class="swiper-slide !h-auto">
                        @include('partials.site.trainer-card', ['trainer' => $trainer, 'bioLimit' => 90])
                    </div>
                @endforeach
            </div>
        </div>
        <div class="carousel-dots mt-8 justify-center text-white" data-dots></div>

        <div class="mt-12 flex justify-center" data-reveal>
            <a href="{{ $gymContact->whatsappUrl('Hi, I want to book a personal training session') }}" target="_blank" rel="noopener" class="btn btn-whatsapp">@icon('whatsapp', '') Book your personal training</a>
        </div>
    </div>
</section>
@endif

{{-- ---------- Why ChampionClub ---------- --}}
<section class="bg-bone-100 pt-12 pb-14 text-ink-950 lg:pt-14 lg:pb-16">
    <div class="wrap grid gap-14 lg:grid-cols-2 lg:gap-20">
        <div class="relative lg:sticky lg:top-28 lg:self-start" data-reveal="left">
            @img('why', 'aspect-[4/5] w-full lg:aspect-[3/4]')
            <div class="absolute -bottom-6 left-6 right-6 flex items-center gap-4 bg-gold-500 p-5 shadow-xl sm:right-auto sm:max-w-sm">
                <span class="inline-flex size-12 shrink-0 items-center justify-center rounded-md bg-ink-950 text-xl text-gold-400">@icon('bolt', '')</span>
                <p class="font-display text-2xl leading-tight font-bold">Your first session is on us.</p>
            </div>
        </div>

        <div class="pt-6 lg:pt-0">
            <p class="eyebrow !text-gold-700" data-reveal>Why ChampionClub</p>
            <h2 class="display-lg mt-5" data-reveal>What training here actually looks like.</h2>

            <ol class="mt-12 divide-y divide-ink-950/10 border-y border-ink-950/10">
                @foreach([
                    ['title' => 'Coaches who coach', 'text' => 'Every session is supervised by a certified trainer who corrects your form, not just your rep count.'],
                    ['title' => 'Equipment built for serious training', 'text' => 'Competition-grade barbells and platforms, maintained on a weekly schedule — not "when someone complains."'],
                    ['title' => 'A community that pushes you', 'text' => 'Train alongside people who show up at 6 AM because they want to, not because an app reminded them.'],
                    ['title' => 'No lock-in contracts', 'text' => 'Pause or cancel any time. Every plan includes one free freeze per quarter for travel or medical reasons.'],
                ] as $reason)
                    <li class="grid grid-cols-[auto_1fr] gap-6 py-8" data-reveal>
                        <span class="font-display text-5xl leading-none font-extrabold text-gold-600 lg:text-6xl">{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>
                        <div>
                            <h3 class="font-display text-[1.75rem] leading-tight">{{ $reason['title'] }}</h3>
                            <p class="mt-2 leading-relaxed text-ink-950/65">{{ $reason['text'] }}</p>
                        </div>
                    </li>
                @endforeach
            </ol>

            <div class="mt-10 flex flex-wrap gap-3" data-reveal>
                <a href="{{ route('gym.trial') }}" class="btn btn-dark">Book a free trial @icon('arrow', 'btn-arrow')</a>
                <a href="{{ $gymContact->callUrl() }}" class="btn btn-outline-dark">@icon('phone', '') Call us</a>
            </div>
        </div>
    </div>
</section>

{{-- ---------- Membership ---------- --}}
@if($plans->isNotEmpty())
@php $featuredIndex = max(0, $plans->search(fn ($p) => $p->is_featured)); @endphp
<section id="membership" class="membership relative isolate overflow-hidden bg-ink-950 pt-12 pb-14 lg:pt-14 lg:pb-16" data-carousel="plans" data-initial="{{ $featuredIndex }}">
    @img('membership-bg', 'absolute inset-x-0 top-0 -z-20 h-[520px] w-full object-cover opacity-30')
    <div class="absolute inset-x-0 top-0 -z-10 h-[520px] bg-gradient-to-b from-ink-950/30 to-ink-950"></div>

    <div class="wrap">
        <div class="mx-auto max-w-2xl text-center" data-reveal>
            <p class="eyebrow justify-center">Membership</p>
            <h2 class="display-lg mt-5 text-white">Membership that matches your intensity.</h2>
            <p class="lead mt-5 text-white/65">No lock-in contracts. Pause or cancel any time.</p>
        </div>

        <div class="plans-slider swiper mt-16 !overflow-visible !pt-6" style="--initial: {{ $featuredIndex }}" data-reveal>
            <div class="swiper-wrapper">
                @foreach($plans as $plan)
                    <div class="swiper-slide !h-auto px-2 lg:px-0 {{ $loop->index === $featuredIndex ? 'swiper-slide-active' : '' }}">
                        @include('partials.site.plan-card', ['plan' => $plan])
                    </div>
                @endforeach
            </div>
        </div>
        <div class="carousel-dots mt-10 justify-center text-white lg:hidden" data-dots></div>

        <div class="mt-14 flex flex-col items-center gap-5 sm:flex-row sm:justify-center" data-reveal>
            <a href="{{ $gymContact->whatsappUrl() }}" target="_blank" rel="noopener" class="btn btn-whatsapp">@icon('whatsapp', '') Get your membership</a>
            <a href="{{ route('gym.membership') }}" class="link-arrow">Plan details &amp; FAQs @icon('arrow', '')</a>
        </div>
    </div>
</section>
@endif

{{-- ---------- Limited time offers ---------- --}}
@if($featuredOffer)
<section id="offers" class="home-offers bg-bone-100 pt-12 pb-14 text-ink-950 lg:pt-14 lg:pb-16">
    <div class="wrap">
        <div class="mx-auto max-w-2xl text-center" data-reveal>
            <p class="eyebrow justify-center !text-gold-700">@icon('fire', 'text-ember-500') Limited time</p>
            <h2 class="display-lg mt-5">Limited time offers</h2>
        </div>

        <div class="offers-grid offers-grid-compact mt-14" data-reveal>
            @include('partials.offer-card', ['offer' => $featuredOffer])
        </div>

        <div class="mt-10 flex flex-col items-center gap-4" data-reveal>
            <a href="{{ route('gym.offers') }}" class="btn btn-outline-dark">
                Go to offers
                @if($moreOffers > 0)
                    <span class="more-count rounded-full bg-gold-500 px-2.5 py-0.5 text-xs font-extrabold text-ink-950">+{{ $moreOffers }} more</span>
                @endif
            </a>
            <a href="{{ $gymContact->whatsappUrl('Hi, I want to know about your current offer') }}" target="_blank" rel="noopener" class="btn btn-whatsapp">@icon('whatsapp', '') Get the offer</a>
        </div>
    </div>
</section>
@endif

{{-- ---------- Member stories ---------- --}}
<section id="reviews" class="testimonials relative isolate overflow-hidden bg-ink-950 pt-12 pb-14 lg:pt-14 lg:pb-16" @if($reviews->isNotEmpty()) data-carousel="reviews" @endif>
    @img('testimonials-bg', 'absolute inset-0 -z-20 size-full object-cover opacity-25')
    <div class="absolute inset-0 -z-10 bg-gradient-to-b from-ink-950 via-ink-950/80 to-ink-950"></div>

    <div class="wrap">
        <div class="flex flex-col gap-8 lg:flex-row lg:items-end lg:justify-between">
            <div data-reveal>
                <p class="eyebrow">Member stories</p>
                <h2 class="display-lg mt-5 text-white">What our members say</h2>
            </div>
            <div class="flex items-center gap-3" data-reveal>
                <a href="#write-review" class="btn btn-ghost btn-sm">@icon('star-solid', 'text-gold-400') Write a review</a>
                @if($reviews->isNotEmpty())
                    <button type="button" class="carousel-btn carousel-btn-dark" data-prev aria-label="Previous reviews">@icon('arrow', 'rotate-180')</button>
                    <button type="button" class="carousel-btn carousel-btn-dark" data-next aria-label="Next reviews">@icon('arrow', '')</button>
                @endif
            </div>
        </div>

        @if($reviews->isNotEmpty())
        <div class="swiper mt-12" data-reveal>
            <div class="swiper-wrapper">
                @foreach($reviews as $review)
                    @php $rating = (int) $review->rating; @endphp
                    <figure class="swiper-slide !h-auto">
                        <div class="flex h-full flex-col border border-white/10 bg-ink-900/85 p-7 backdrop-blur sm:p-8">
                            <div class="flex items-center justify-between gap-4">
                                @icon('quote', 'text-3xl text-gold-500')
                                <span class="flex gap-1 text-sm" role="img" aria-label="Rated {{ $rating }} out of 5">
                                    @for($s = 1; $s <= 5; $s++)
                                        @icon('star-solid', $s <= $rating ? 'text-gold-400' : 'text-white/20')
                                    @endfor
                                </span>
                            </div>
                            <blockquote class="mt-6 flex-1 font-display text-[1.4rem] leading-snug font-semibold text-white">“{{ $review->quote }}”</blockquote>
                            <figcaption class="mt-7 flex items-center gap-4 border-t border-white/10 pt-6">
                                <span class="inline-flex size-12 shrink-0 items-center justify-center rounded-full bg-gold-500 font-display text-lg font-extrabold text-ink-950">{{ $review->initials() }}</span>
                                <strong class="text-white">{{ $review->name }}</strong>
                            </figcaption>
                        </div>
                    </figure>
                @endforeach
            </div>
        </div>
        <div class="carousel-dots mt-8 justify-center text-white" data-dots></div>
        @endif

        @include('partials.site.review-form')
    </div>
</section>

{{-- ---------- Video reviews ---------- --}}
@if($videos->isNotEmpty())
<section id="video-reviews" class="bg-bone-100 pt-12 pb-14 text-ink-950 lg:pt-14 lg:pb-16" data-carousel="videos">
    <div class="wrap">
        <div class="flex flex-col gap-8 lg:flex-row lg:items-end lg:justify-between">
            <div data-reveal>
                <p class="eyebrow !text-gold-700">Video reviews</p>
                <h2 class="display-lg mt-5">Hear it from our members.</h2>
            </div>
            <div class="flex items-center gap-3" data-reveal>
                <button type="button" class="carousel-btn carousel-btn-light" data-prev aria-label="Previous videos">@icon('arrow', 'rotate-180')</button>
                <button type="button" class="carousel-btn carousel-btn-light" data-next aria-label="Next videos">@icon('arrow', '')</button>
            </div>
        </div>

        <div class="swiper mt-12" data-reveal>
            <div class="swiper-wrapper">
                @foreach($videos as $video)
                    <div class="swiper-slide !h-auto">
                        <article class="flex h-full flex-col overflow-hidden rounded-md bg-ink-950 shadow-xl">
                            <div class="video-embed relative aspect-video bg-ink-900" data-video-embed>
                                <button type="button" class="video-play group absolute inset-0 size-full" data-video-src="{{ $video->embedUrl() }}" aria-label="Play video: {{ $video->title }}">
                                    <img src="{{ $video->thumbnailUrl() }}" alt="" width="480" height="360" loading="lazy" decoding="async" class="absolute inset-0 size-full object-cover transition duration-500 group-hover:scale-[1.04]">
                                    <span class="absolute inset-0 bg-gradient-to-t from-ink-950/70 via-ink-950/10 to-transparent"></span>
                                    <span class="video-play-icon">@icon('play', '')</span>
                                </button>
                            </div>
                            <div class="flex flex-1 flex-col p-5 sm:p-6">
                                <h3 class="font-display text-[1.45rem] leading-tight text-white">{{ $video->title }}</h3>
                                @if($video->name)
                                    <p class="mt-2 text-sm text-white/55">{{ $video->name }}</p>
                                @endif
                            </div>
                        </article>
                    </div>
                @endforeach
            </div>
        </div>
        <div class="carousel-dots mt-8 justify-center text-ink-950" data-dots></div>

        @if(config('gym.social.youtube'))
            <div class="mt-10 flex justify-center" data-reveal>
                <a href="{{ config('gym.social.youtube') }}" target="_blank" rel="noopener" class="btn btn-dark">@icon('youtube', 'text-[#ff0033]') Watch more on YouTube</a>
            </div>
        @endif
    </div>
</section>
@endif

{{-- ---------- Free trial ---------- --}}
<section id="free-trial" class="grid bg-ink-900 lg:grid-cols-2">
    <div class="relative isolate min-h-[420px] overflow-hidden lg:min-h-full">
        @img('lead', 'absolute inset-0 -z-10 size-full')
        <div class="absolute inset-0 -z-10 bg-gradient-to-t from-ink-950 via-ink-950/40 to-transparent"></div>
        <div class="flex h-full flex-col justify-end p-8 sm:p-12 lg:p-16">
            <p class="eyebrow">Free trial</p>
            <p class="display-lg mt-5 max-w-md text-white">Your first session is on us.</p>
            <ol class="mt-8 grid gap-3 text-white/80">
                <li class="flex items-center gap-3"><span class="inline-flex size-7 items-center justify-center rounded-full bg-gold-500 text-xs font-extrabold text-ink-950">1</span> Share your name and number</li>
                <li class="flex items-center gap-3"><span class="inline-flex size-7 items-center justify-center rounded-full bg-gold-500 text-xs font-extrabold text-ink-950">2</span> We call to fix a time that suits you</li>
                <li class="flex items-center gap-3"><span class="inline-flex size-7 items-center justify-center rounded-full bg-gold-500 text-xs font-extrabold text-ink-950">3</span> Meet a coach and train on the floor</li>
            </ol>
        </div>
    </div>
    <div class="flex items-center p-8 sm:p-12 lg:p-16 xl:p-20">
        <div class="w-full max-w-lg" data-reveal>
            <h2 class="display-md text-white">Book your free trial</h2>
            <p class="mt-3 mb-8 text-white/60">Walk in, meet the coaches, use the floor. No pressure, no sales pitch.</p>
            @include('partials.site.lead-form', ['formId' => 'home-lead'])
        </div>
    </div>
</section>

{{-- ---------- Location ---------- --}}
<section class="bg-bone-50 pt-12 pb-14 text-ink-950 lg:pt-14 lg:pb-16">
    <div class="wrap grid gap-12 lg:grid-cols-12 lg:items-center">
        <div class="lg:col-span-5" data-reveal>
            <p class="eyebrow !text-gold-700">Visit us</p>
            <h2 class="display-lg mt-5">Come see the floor.</h2>
            <ul class="mt-10 grid gap-6">
                <li class="flex gap-4">
                    <span class="inline-flex size-11 shrink-0 items-center justify-center rounded-md bg-ink-950 text-lg text-gold-400">@icon('pin', '')</span>
                    <div>
                        <p class="font-bold">Address</p>
                        <address class="mt-1 text-ink-950/70 not-italic">{{ $gymContact->addressLine() }}</address>
                        <a href="{{ $gymContact->mapsUrl() }}" target="_blank" rel="noopener" class="mt-2 inline-flex items-center gap-1.5 text-sm font-bold text-gold-700 hover:underline">Get directions @icon('external', '')</a>
                    </div>
                </li>
                <li class="flex gap-4">
                    <span class="inline-flex size-11 shrink-0 items-center justify-center rounded-md bg-ink-950 text-lg text-gold-400">@icon('clock', '')</span>
                    <div>
                        <p class="font-bold">Opening hours</p>
                        @foreach($gymContact->hours() as $h)
                            <p class="mt-1 text-ink-950/70">{{ $h['days'] }}: {{ $h['time'] }}</p>
                        @endforeach
                    </div>
                </li>
                <li class="flex gap-4">
                    <span class="inline-flex size-11 shrink-0 items-center justify-center rounded-md bg-ink-950 text-lg text-gold-400">@icon('phone', '')</span>
                    <div>
                        <p class="font-bold">Call or email</p>
                        <a href="{{ $gymContact->callUrl() }}" class="mt-1 block text-ink-950/70 hover:text-ink-950">{{ $gymContact->displayPhone() }}</a>
                        <a href="mailto:{{ $gymContact->email() }}" class="block text-ink-950/70 hover:text-ink-950">{{ $gymContact->email() }}</a>
                    </div>
                </li>
            </ul>
            <div class="mt-10 flex flex-wrap gap-3">
                <a href="{{ $gymContact->callUrl() }}" class="btn btn-dark">@icon('phone', '') Call us</a>
                <a href="{{ $gymContact->whatsappUrl() }}" target="_blank" rel="noopener" class="btn btn-whatsapp">@icon('whatsapp', '') WhatsApp us</a>
            </div>
        </div>
        <div class="lg:col-span-7" data-reveal="zoom">
            <div class="aspect-[4/3] overflow-hidden rounded-sm border border-ink-950/10 bg-bone-200 shadow-xl lg:aspect-[16/11]">
                @include('partials.site.map')
            </div>
        </div>
    </div>
</section>

@endsection
