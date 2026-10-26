<div class="map-embed relative size-full" data-map="{{ $gymContact->mapEmbedUrl() }}">
    <div class="map-placeholder absolute inset-0 flex flex-col items-center justify-center gap-3 bg-[radial-gradient(circle_at_50%_45%,rgba(212,165,55,0.18),transparent_60%)] p-6 text-center text-ink-950">
        <span class="inline-flex size-14 items-center justify-center rounded-full bg-ink-950 text-2xl text-gold-400">@icon('pin', '')</span>
        <p class="max-w-xs text-sm font-semibold">{{ $gymContact->addressLine() }}</p>
        <a href="{{ $gymContact->mapsUrl() }}" target="_blank" rel="noopener" class="link-arrow text-sm font-bold text-gold-700">Open in Google Maps @icon('external', '')</a>
    </div>
    <noscript>
        <iframe src="{{ $gymContact->mapEmbedUrl() }}" title="Map showing ChampionClub location" loading="lazy" referrerpolicy="no-referrer-when-downgrade" class="absolute inset-0 size-full border-0 grayscale-[35%]"></iframe>
    </noscript>
</div>
