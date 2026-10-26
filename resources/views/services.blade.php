@extends('layouts.app')

@section('title', 'Services — Coached Training | ChampionClub ' . config('gym.address.locality'))
@section('description', 'Explore coached services at ChampionClub ' . config('gym.address.locality') . ($services->isNotEmpty() ? ': ' . $services->pluck('title')->implode(', ') . '.' : '.'))
@section('og_image', 'page-programs')

@section('content')

<x-site.page-hero image="page-programs" eyebrow="Services" title="Coached services for every goal." crumb="Services">
    Every service is coached, not just supervised. Pick one, or mix a few once you know what you like.
    <x-slot:actions>
        <a href="{{ route('gym.trial') }}" class="btn btn-gold">Book a free trial @icon('arrow', 'btn-arrow')</a>
        <a href="{{ $gymContact->whatsappUrl('Hi, I want help choosing a service') }}" target="_blank" rel="noopener" class="btn btn-ghost">@icon('whatsapp', '') Help me choose</a>
    </x-slot:actions>
</x-site.page-hero>

<section class="bg-bone-100 pt-12 pb-14 text-ink-950 lg:pt-14 lg:pb-16">
    <div class="wrap">
        @if($services->isEmpty())
            <div class="mx-auto max-w-xl border border-white/10 bg-ink-900 p-10 text-center">
                <span class="icon-tile">@icon('dumbbell', '')</span>
                <h2 class="display-md mt-6 text-white">Services will be added soon.</h2>
                <a href="{{ route('gym.contact') }}" class="btn btn-gold mt-8">Ask us about training</a>
            </div>
        @else
            <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                @foreach($services as $service)
                    <div data-reveal style="--reveal-delay: {{ ($loop->index % 3) * 0.08 }}s">
                        @include('partials.site.service-card', ['service' => $service])
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</section>

@include('partials.site.cta-band', ['image' => 'gallery-6', 'title' => 'Not sure which service fits?', 'text' => 'Book a free trial and a coach will help you pick the right one.'])

@endsection
