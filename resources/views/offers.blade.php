@extends('layouts.app')

@section('title', 'Gym Offers & Festival Deals | ChampionClub ' . config('gym.address.locality'))
@section('description', 'Current festival offers and limited-time membership deals at ChampionClub ' . config('gym.address.locality') . '. Claim an offer before the last date.')
@section('og_image', 'page-offers')

@section('content')

<x-site.page-hero image="page-offers" eyebrow="Limited time" title="Event offers" crumb="Offers">
    Festival specials on memberships and coaching. Grab them before the last date.
</x-site.page-hero>

<section class="offers-section bg-bone-100 pt-12 pb-14 text-ink-950 lg:pt-14 lg:pb-16">
    <div class="wrap">
        @if($offers->isEmpty())
            <div class="offers-empty mx-auto max-w-2xl border border-white/10 bg-ink-900 p-10 text-center sm:p-14" data-reveal>
                <span class="offers-empty-icon icon-tile size-16">@icon('gift', '!text-3xl')</span>
                <h2 class="display-md mt-7 text-white">Sorry, there are currently no offers.</h2>
                <p class="mx-auto mt-4 max-w-md text-white/60">We run special offers on festivals like New Year, Holi, Diwali and Christmas. Check back soon — or ask us about current plans.</p>
                <div class="mt-9 flex flex-wrap justify-center gap-3">
                    <a href="{{ route('gym.membership') }}" class="btn btn-gold">View membership plans</a>
                    <a href="{{ $gymContact->whatsappUrl() }}" target="_blank" rel="noopener" class="btn btn-ghost">@icon('whatsapp', '') WhatsApp us</a>
                </div>
            </div>
        @else
            <div class="offers-grid">
                @foreach($offers as $offer)
                    <div data-reveal>
                        @include('partials.offer-card', ['offer' => $offer])
                    </div>
                @endforeach
            </div>
            <div class="mt-12 flex justify-center" data-reveal>
                <a href="{{ $gymContact->whatsappUrl('Hi, I want to know about your current offer') }}" target="_blank" rel="noopener" class="btn btn-whatsapp">@icon('whatsapp', '') Get the offer</a>
            </div>
        @endif
    </div>
</section>

@endsection
