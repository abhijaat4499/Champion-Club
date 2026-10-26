@php
    $tierColor = ['gold' => 'bg-gold-500', 'silver' => 'bg-neutral-300', 'bronze' => 'bg-[#b5763a]'][$plan->color] ?? 'bg-gold-500';
@endphp

<article class="plan-card relative flex h-full flex-col rounded-md border p-7 sm:p-8 {{ $plan->is_featured ? 'border-gold-500/70 bg-gradient-to-b from-gold-500/[0.16] via-ink-850 to-ink-850 shadow-[0_40px_80px_-40px_rgba(212,165,55,0.55)]' : 'border-white/10 bg-ink-850' }}">
    @if($plan->is_featured)
        <span class="absolute -top-3.5 left-7 rounded-full bg-gold-500 px-3.5 py-1 text-[0.7rem] font-extrabold tracking-[0.14em] text-ink-950 uppercase">Most popular</span>
    @endif

    <div class="flex items-center justify-between gap-4">
        <h3 class="font-display text-[2rem] leading-none text-white">{{ $plan->name }}</h3>
        <span class="inline-flex items-center gap-2 text-[0.7rem] font-bold tracking-[0.16em] text-white/50 uppercase">
            <span class="size-2.5 rounded-full {{ $tierColor }}"></span>{{ $plan->color }}
        </span>
    </div>

    <p class="mt-7 flex items-baseline gap-1.5">
        <span class="font-display text-6xl leading-none font-extrabold text-white">₹{{ number_format($plan->price) }}</span>
        <span class="text-sm font-semibold text-white/50">{{ $plan->period }}</span>
    </p>

    <ul class="mt-7 flex-1 space-y-3.5 border-t border-white/10 pt-7 text-[0.95rem]">
        @foreach($plan->featureList() as $feature)
            <li class="flex gap-3 text-white/80">
                @icon('check', 'mt-1 text-sm text-gold-400')
                <span>{{ $feature }}</span>
            </li>
        @endforeach
    </ul>

    <a href="{{ route('gym.book.plan', $plan) }}" class="btn mt-8 w-full {{ $plan->is_featured ? 'btn-gold' : 'btn-ghost' }}">
        {{ $plan->button_text }} @icon('arrow', 'btn-arrow')
    </a>
</article>
