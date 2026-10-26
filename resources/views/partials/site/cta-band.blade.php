@php
    $image = $image ?? 'statement';
    $title = $title ?? 'Your first session is on us.';
    $text = $text ?? 'Walk in, meet the coaches, use the floor. No pressure, no sales pitch.';
@endphp

<section class="relative isolate overflow-hidden bg-ink-800 py-14 lg:py-16">
    @img($image, 'absolute inset-0 -z-20 size-full object-cover opacity-90')
    <div class="absolute inset-0 -z-10 bg-gradient-to-r from-ink-950/90 via-ink-950/55 to-ink-950/5"></div>

    <div class="wrap grid items-end gap-10 lg:grid-cols-12">
        <div class="lg:col-span-7" data-reveal>
            <p class="eyebrow">Free trial</p>
            <h2 class="display-lg mt-5 text-white">{{ $title }}</h2>
            <p class="lead mt-5 max-w-xl text-white/70">{{ $text }}</p>
        </div>
        <div class="grid gap-3 sm:grid-cols-3 lg:col-span-4 lg:col-start-9 lg:grid-cols-1" data-reveal style="--reveal-delay: .15s">
            <a href="{{ route('gym.trial') }}" class="btn btn-gold w-full">Book a free trial @icon('arrow', 'btn-arrow')</a>
            <a href="{{ $gymContact->whatsappUrl() }}" target="_blank" rel="noopener" class="btn btn-whatsapp w-full">@icon('whatsapp', '') WhatsApp us</a>
            <a href="{{ $gymContact->callUrl() }}" class="btn btn-ghost w-full">@icon('phone', '') Call {{ $gymContact->displayPhone() }}</a>
        </div>
    </div>
</section>
