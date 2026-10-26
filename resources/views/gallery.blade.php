@extends('layouts.app')

@section('title', 'Gallery — Inside ChampionClub | Gym in ' . config('gym.address.locality'))
@section('description', 'Take a look inside ChampionClub ' . config('gym.address.locality') . ': the reception, lounge, weights floor, cardio zone, changing rooms, lockers and washrooms.')
@section('og_image', 'page-facilities')

@section('content')

<x-site.page-hero image="page-facilities" eyebrow="Gallery" title="Take a look inside." crumb="Gallery">
    From the reception to the lockers — see the spaces you'll train in before you visit.
    <x-slot:actions>
        <a href="{{ route('gym.trial') }}" class="btn btn-gold">Book a free trial @icon('arrow', 'btn-arrow')</a>
        <a href="{{ $gymContact->mapsUrl() }}" target="_blank" rel="noopener" class="btn btn-ghost">@icon('pin', '') Visit us</a>
    </x-slot:actions>
</x-site.page-hero>

<section class="bg-bone-100 pt-12 pb-14 text-ink-950 lg:pt-14 lg:pb-16">
    <div class="wrap">
        @if($photos->isEmpty())
            <div class="mx-auto max-w-xl border border-white/10 bg-ink-900 p-10 text-center">
                <span class="icon-tile">@icon('sparkle', '')</span>
                <h2 class="display-md mt-6 text-white">Photos will be added soon.</h2>
                <a href="{{ route('gym.facilities') }}" class="btn btn-gold mt-8">Explore the facilities</a>
            </div>
        @else
            <div class="gallery-grid" data-gallery>
                @foreach($photos as $photo)
                    <figure class="gallery-item" data-reveal style="--reveal-delay: {{ ($loop->index % 3) * 0.06 }}s">
                        <button type="button" class="gallery-open group" data-gallery-item data-src="{{ $photo->url() }}" data-alt="{{ $photo->alt }}" data-caption="{{ $photo->caption }}" aria-label="Open photo: {{ $photo->alt }}">
                            <img src="{{ $photo->url() }}" alt="{{ $photo->alt }}" width="{{ $photo->width ?: 1400 }}" height="{{ $photo->height ?: 933 }}" loading="{{ $loop->index < 3 ? 'eager' : 'lazy' }}" decoding="async" class="absolute inset-0 size-full object-cover transition duration-700 group-hover:scale-[1.04]">
                            <span class="gallery-zoom" aria-hidden="true">@icon('external', '')</span>
                            @if($photo->caption)
                                <span class="gallery-caption">{{ $photo->caption }}</span>
                            @endif
                        </button>
                    </figure>
                @endforeach
            </div>
        @endif
    </div>
</section>

@if($photos->isNotEmpty())
    <dialog class="gallery-lightbox" data-lightbox aria-label="Photo viewer">
        <div class="gallery-lightbox-inner">
            <button type="button" class="gallery-lightbox-btn gallery-lightbox-close" data-lightbox-close aria-label="Close">@icon('close', '')</button>
            <button type="button" class="gallery-lightbox-btn gallery-lightbox-prev" data-lightbox-prev aria-label="Previous photo">@icon('arrow', 'rotate-180')</button>
            <figure class="gallery-lightbox-figure">
                <img src="" alt="" data-lightbox-img>
                <figcaption data-lightbox-caption></figcaption>
            </figure>
            <button type="button" class="gallery-lightbox-btn gallery-lightbox-next" data-lightbox-next aria-label="Next photo">@icon('arrow', '')</button>
            <p class="gallery-lightbox-count" data-lightbox-count></p>
        </div>
    </dialog>
@endif

@include('partials.site.cta-band', ['image' => 'gallery-1', 'title' => 'Like what you see?', 'text' => 'Book a free trial and see the floor in person.'])

@endsection
