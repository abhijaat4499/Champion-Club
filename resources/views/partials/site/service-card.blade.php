<a href="{{ route('gym.service', $service) }}" class="zoom-media group flex h-full flex-col overflow-hidden rounded-md border border-white/10 bg-ink-850 transition hover:border-gold-500/60">
    <div class="relative aspect-[4/3] overflow-hidden bg-ink-800">
        @if($service->imageUrl())
            <img src="{{ $service->imageUrl() }}" style="{{ $service->imageFocus() }}" alt="{{ $service->title }}" width="640" height="480" loading="lazy" decoding="async" class="absolute inset-0 size-full object-cover">
        @else
            @img(\App\Models\Service::FALLBACK_IMAGE, 'absolute inset-0 size-full', false, $service->title)
        @endif
        <div class="absolute inset-0 bg-gradient-to-t from-ink-850 via-transparent to-transparent"></div>
        <span class="absolute bottom-4 left-5 inline-flex size-12 items-center justify-center rounded-md bg-gold-500 text-xl text-ink-950">@icon($service->iconName(), '')</span>
    </div>
    <div class="flex flex-1 flex-col p-6">
        <h3 class="font-display text-[1.75rem] leading-tight text-white">{{ $service->title }}</h3>
        <p class="mt-3 flex-1 text-[0.95rem] leading-relaxed text-white/65">{{ $service->summary }}</p>
        <span class="link-arrow mt-6 self-start">View service @icon('arrow', 'transition-transform duration-300 group-hover:translate-x-1')</span>
    </div>
</a>
