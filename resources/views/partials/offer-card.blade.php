@php
    $event = array_key_exists($offer->event, \App\Models\Offer::EVENTS) ? $offer->event : 'other';
    $until = $offer->valid_until;
    $daysLeft = $offer->daysLeft();
    $discount = $offer->discountPercent();
    $lines = $offer->descriptionLines();

    $newYear = $offer->newYearNumber();

    $greeting = $offer->greeting();
@endphp

<article class="offer-card offer-{{ $event }}">

    {{-- ---------- Decoration ---------- --}}
    <div class="offer-deco" aria-hidden="true">
        @switch($event)
            @case('new_year')
                <span class="ny-year">{{ $newYear }}</span>
                @icon('confetti', 'ny-burst ny-burst-1')
                @icon('cheers', 'ny-burst ny-burst-2')
                @icon('sparkle', 'ny-burst ny-burst-3')
                <span class="ny-confetti"></span>
                @break

            @case('diwali')
                <span class="dw-toran"></span>
                <span class="dw-rangoli"></span>
                <div class="dw-diyas">
                    @for($i = 0; $i < 3; $i++)
                        @icon('diya', 'dw-diya')
                    @endfor
                </div>
                @break

            @case('holi')
                <span class="holi-splash s1"></span>
                <span class="holi-splash s2"></span>
                <span class="holi-splash s3"></span>
                <span class="holi-splash s4"></span>
                <span class="holi-splash s5"></span>
                <span class="holi-dots"></span>
                @icon('palette', 'holi-palette')
                @break

            @case('christmas')
                <span class="xm-stripe"></span>
                <span class="xm-snow"></span>
                <span class="xm-tree">
                    @icon('xstar', 'xm-star')
                    @icon('xtree', 'xm-leaf')
                </span>
                @icon('snowflake', 'xm-flake')
                @break

            @default
                <span class="std-icon">
                    @icon('gift', '')
                </span>
        @endswitch
    </div>

    {{-- ---------- Details ---------- --}}
    <div class="offer-body">
        <span class="offer-greeting">{{ $greeting }}</span>
        <h3 class="offer-title">{{ $offer->title }}</h3>

        <div class="offer-price">
            <span class="offer-now">₹{{ number_format($offer->price) }}</span>
            @if($discount)
                <s class="offer-was">₹{{ number_format($offer->original_price) }}</s>
                <span class="offer-off">{{ $discount }}% OFF</span>
            @endif
        </div>

        @if(count($lines) > 1)
            <ul class="offer-points">
                @foreach($lines as $line)
                    <li>{{ $line }}</li>
                @endforeach
            </ul>
        @else
            <p class="offer-desc">{{ $lines[0] ?? '' }}</p>
        @endif

        <div class="offer-foot">
            <span class="offer-deadline">
                @icon('clock', '')
                Valid till {{ $until->format('d M Y') }}
                <strong>
                    @if($daysLeft <= 0)
                        · Last day today
                    @elseif($daysLeft === 1)
                        · 1 day left
                    @else
                        · {{ $daysLeft }} days left
                    @endif
                </strong>
            </span>
            <a class="btn offer-cta" href="{{ route('gym.book.offer', $offer) }}">Claim offer</a>
        </div>
    </div>
</article>
