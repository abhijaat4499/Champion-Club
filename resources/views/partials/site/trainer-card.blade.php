@php
    $bioLimit = $bioLimit ?? 120;
    $long = mb_strlen($trainer->bio) > $bioLimit;
@endphp

<article class="trainer-card group flex h-full flex-col overflow-hidden rounded-md border border-white/10 bg-ink-850">
    <div class="zoom-media relative aspect-[4/5] overflow-hidden bg-ink-800">
        @if($trainer->photo)
            <img src="{{ asset('storage/' . $trainer->photo) }}" alt="{{ $trainer->name }}, {{ $trainer->role }}" loading="lazy" decoding="async" class="size-full object-cover object-[50%_20%]">
        @else
            <div class="flex size-full items-center justify-center bg-[radial-gradient(circle_at_50%_40%,rgba(212,165,55,0.28),transparent_62%)]">
                <span class="flex size-44 items-center justify-center rounded-full border border-gold-500/50 bg-ink-900/60 shadow-[0_0_0_14px_rgba(212,165,55,0.06)]">
                    <span class="bg-gradient-to-b from-gold-200 to-gold-600 bg-clip-text font-display text-[5.5rem] leading-none font-extrabold text-transparent">{{ $trainer->initials() }}</span>
                </span>
            </div>
        @endif
        <div class="absolute inset-x-0 bottom-0 h-1/2 bg-gradient-to-t from-ink-850 to-transparent"></div>
        <span class="absolute bottom-4 left-5 rounded-full bg-gold-500 px-3 py-1 text-[0.7rem] font-extrabold tracking-[0.12em] text-ink-950 uppercase">{{ $trainer->role }}</span>
    </div>

    <div class="flex flex-1 flex-col p-6">
        <h3 class="font-display text-[1.85rem] leading-none text-white capitalize">{{ $trainer->name }}</h3>
        <p class="mt-3 flex-1 text-[0.95rem] leading-relaxed text-white/65">
            @if($long)
                {{ rtrim(\Illuminate\Support\Str::limit($trainer->bio, $bioLimit, '')) }}… <a href="{{ route('gym.trainers') }}" class="read-more font-semibold text-gold-400 underline underline-offset-4">read more</a>
            @else
                {{ $trainer->bio }}
            @endif
        </p>
        <a href="{{ route('gym.book.trainer', $trainer) }}" class="btn btn-ghost btn-sm mt-6 self-start">Book slot @icon('arrow', 'btn-arrow')</a>
    </div>
</article>
