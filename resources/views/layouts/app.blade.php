@php
    $siteName = config('gym.name');
    $pageTitle = trim($__env->yieldContent('title', $siteName . ' — Strength & Conditioning Gym in ' . config('gym.address.locality')));
    $pageDescription = trim($__env->yieldContent('description', 'ChampionClub is a strength and conditioning gym in ' . config('gym.address.locality') . ' with certified coaches, competition-grade equipment and no lock-in contracts. Book a free trial session.'));
    $canonical = (request()->is('/') || request()->routeIs('gym.home')) ? url('/') : url()->current();
    $ogImage = asset(config('site-images.' . trim($__env->yieldContent('og_image', 'hero-strength')) . '.src', 'images/site/hero-strength.webp'));

    $nav = [
        ['route' => 'gym.about', 'label' => 'About'],
        ['route' => 'gym.services', 'label' => 'Services', 'match' => ['gym.services', 'gym.service', 'gym.book.service']],
        ['route' => 'gym.trainers', 'label' => 'Trainers', 'match' => ['gym.trainers', 'gym.book.trainer']],
        ['route' => 'gym.membership', 'label' => 'Membership', 'match' => ['gym.membership', 'gym.book.plan']],
        ['route' => 'gym.offers', 'label' => 'Offers', 'match' => ['gym.offers', 'gym.book.offer']],
        ['route' => 'gym.gallery', 'label' => 'Gallery'],
        ['route' => 'gym.contact', 'label' => 'Contact'],
    ];

    $schema = [
        '@context' => 'https://schema.org',
        '@type' => 'ExerciseGym',
        'name' => $siteName,
        'url' => url('/'),
        'image' => asset(config('site-images.hero-facility.src')),
        'logo' => asset('images/logo.jpg'),
        'telephone' => $gymContact->schemaPhone(),
        'hasMap' => $gymContact->mapsUrl(),
        'email' => $gymContact->email(),
        'address' => [
            '@type' => 'PostalAddress',
            'streetAddress' => config('gym.address.street'),
            'addressLocality' => config('gym.address.locality'),
            'addressRegion' => config('gym.address.region'),
            'addressCountry' => config('gym.address.country'),
        ],
        'openingHours' => collect(config('gym.hours'))->pluck('schema')->all(),
        'sameAs' => array_values(array_filter(config('gym.social'))),
    ];
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>{{ $pageTitle }}</title>
<meta name="description" content="{{ $pageDescription }}">
<link rel="canonical" href="{{ $canonical }}">
<meta name="theme-color" content="#09090a">

<meta property="og:type" content="website">
<meta property="og:site_name" content="{{ $siteName }}">
<meta property="og:title" content="{{ $pageTitle }}">
<meta property="og:description" content="{{ $pageDescription }}">
<meta property="og:url" content="{{ $canonical }}">
<meta property="og:image" content="{{ $ogImage }}">
<meta name="twitter:card" content="summary_large_image">

@include('partials.fonts')

@stack('preload')

<script>
(function (d) {
    if (window.matchMedia && matchMedia('(prefers-reduced-motion: reduce)').matches) return;
    d.classList.add('js-motion');
    setTimeout(function () { if (!window.__appReady) d.classList.remove('js-motion'); }, 4000);
})(document.documentElement);
</script>

@vite(['resources/css/app.css', 'resources/js/app.js'])

<script type="application/ld+json">{!! json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}</script>
@stack('schema')
</head>
<body class="@yield('body_class') pb-16 lg:pb-0">

<a href="#main" class="sr-only z-[100] rounded bg-gold-500 px-4 py-2 font-bold text-ink-950 focus:not-sr-only focus:fixed focus:top-4 focus:left-4">Skip to content</a>

{{-- ---------- Header ---------- --}}
<header class="site-header fixed inset-x-0 top-0 z-50" data-header>
    <div class="hidden h-10 border-b border-white/10 bg-ink-950 text-[0.8rem] text-white/65 lg:block">
        <div class="wrap flex h-full items-center justify-between">
            <p class="flex items-center gap-2">
                @icon('clock', 'text-gold-500')
                @foreach($gymContact->hours() as $h)
                    <span>{{ $h['days'] }} {{ $h['time'] }}</span>@if(!$loop->last)<span class="text-white/25">·</span>@endif
                @endforeach
            </p>
            <div class="flex items-center gap-6">
                <a href="{{ $gymContact->mapsUrl() }}" target="_blank" rel="noopener" class="flex items-center gap-2 transition hover:text-white">
                    @icon('pin', 'text-gold-500') {{ config('gym.address.locality') }}, {{ config('gym.address.region') }}
                </a>
                <a href="{{ $gymContact->callUrl() }}" class="flex items-center gap-2 font-semibold text-white transition hover:text-gold-400">
                    @icon('phone', 'text-gold-500') {{ $gymContact->displayPhone() }}
                </a>
            </div>
        </div>
    </div>

    <div class="header-bar border-b border-transparent bg-gradient-to-b from-ink-950/80 to-transparent py-4 lg:py-5">
        <div class="wrap flex items-center justify-between gap-6">
            <a href="{{ url('/') }}" class="flex shrink-0 items-center gap-3" aria-label="{{ $siteName }} home">
                <img src="{{ asset('images/logo-sm.webp') }}" alt="" width="44" height="44" class="size-10 rounded-full ring-1 ring-gold-500/60 lg:size-11">
                <span class="font-display text-[1.6rem] leading-none font-extrabold tracking-wide text-white uppercase">Champion<span class="text-gold-500">Club</span></span>
            </a>

            <nav aria-label="Primary" class="hidden xl:block">
                <ul class="flex items-center gap-7">
                    @foreach($nav as $item)
                        @if($item['route'] === 'gym.services' && $navServices->isNotEmpty())
                            <li class="nav-drop">
                                <a href="{{ route($item['route']) }}" class="nav-link inline-flex items-center gap-1.5" aria-haspopup="true" @if(request()->routeIs($item['match'])) aria-current="page" @endif>
                                    {{ $item['label'] }} @icon('down', 'nav-drop-caret text-[0.55rem]')
                                </a>
                                <div class="nav-drop-panel">
                                    <ul class="nav-drop-menu">
                                        @foreach($navServices as $navService)
                                            <li>
                                                <a href="{{ route('gym.service', $navService) }}" class="nav-drop-link" @if(request()->routeIs('gym.service') && request()->route('service')?->is($navService)) aria-current="page" @endif>
                                                    <span class="nav-drop-icon">@icon($navService->iconName(), '')</span>
                                                    {{ $navService->title }}
                                                </a>
                                            </li>
                                        @endforeach
                                    </ul>
                                    <a href="{{ route('gym.services') }}" class="nav-drop-all">All services @icon('arrow', '')</a>
                                </div>
                            </li>
                        @else
                            <li>
                                <a href="{{ route($item['route']) }}" class="nav-link" @if(request()->routeIs($item['match'] ?? $item['route'])) aria-current="page" @endif>{{ $item['label'] }}</a>
                            </li>
                        @endif
                    @endforeach
                </ul>
            </nav>

            <div class="flex items-center gap-2.5">
                <a href="{{ $gymContact->whatsappUrl() }}" target="_blank" rel="noopener" class="nav-whatsapp btn btn-whatsapp btn-sm max-sm:size-11 max-sm:p-0" aria-label="WhatsApp us">
                    @icon('whatsapp', '')<span class="max-sm:hidden">WhatsApp us</span>
                </a>
                <a href="{{ route('gym.trial') }}" class="btn btn-gold btn-sm max-sm:hidden">Free trial</a>
                <button type="button" class="inline-flex size-11 items-center justify-center rounded-[4px] border border-white/20 text-xl text-white transition hover:border-gold-500 hover:text-gold-400 xl:hidden"
                        data-menu-open aria-controls="mobileMenu" aria-expanded="false" aria-label="Open menu">
                    @icon('menu', '')
                </button>
            </div>
        </div>
    </div>
</header>

{{-- ---------- Mobile menu ---------- --}}
<div id="mobileMenu" class="mobile-menu fixed inset-0 z-[60] flex flex-col overflow-y-auto bg-ink-950" data-menu hidden role="dialog" aria-modal="true" aria-label="Menu">
    <div class="wrap flex items-center justify-between py-4">
        <span class="font-display text-[1.6rem] font-extrabold tracking-wide text-white uppercase">Champion<span class="text-gold-500">Club</span></span>
        <button type="button" class="inline-flex size-11 items-center justify-center rounded-[4px] border border-white/20 text-xl text-white" data-menu-close aria-label="Close menu">
            @icon('close', '')
        </button>
    </div>

    <nav aria-label="Mobile" class="wrap flex-1 py-6">
        <ul class="divide-y divide-white/10 border-y border-white/10">
            <li class="menu-item" style="--i: 0">
                <a href="{{ url('/') }}" class="flex items-baseline gap-4 py-4 font-display text-4xl font-bold text-white">
                    <span class="font-sans text-xs font-bold tracking-widest text-gold-500">00</span> Home
                </a>
            </li>
            @foreach($nav as $item)
                <li class="menu-item" style="--i: {{ $loop->iteration }}">
                    <a href="{{ route($item['route']) }}" class="flex items-baseline gap-4 py-4 font-display text-4xl font-bold {{ request()->routeIs($item['match'] ?? $item['route']) ? 'text-gold-400' : 'text-white' }}">
                        <span class="font-sans text-xs font-bold tracking-widest text-gold-500">{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</span> {{ $item['label'] }}
                    </a>
                    @if($item['route'] === 'gym.services' && $navServices->isNotEmpty())
                        <ul class="grid gap-1 pb-4 pl-9">
                            @foreach($navServices as $navService)
                                <li>
                                    <a href="{{ route('gym.service', $navService) }}" class="flex items-center gap-3 py-1.5 text-[0.95rem] font-semibold text-white/70 transition hover:text-gold-400">
                                        @icon($navService->iconName(), 'text-gold-500') {{ $navService->title }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </li>
            @endforeach
        </ul>
    </nav>

    <div class="wrap grid gap-3 pb-8">
        <a href="{{ route('gym.trial') }}" class="btn btn-gold w-full">Book a free trial @icon('arrow', 'btn-arrow')</a>
        <div class="grid grid-cols-2 gap-3">
            <a href="{{ $gymContact->callUrl() }}" class="btn btn-ghost w-full">@icon('phone', '') Call us</a>
            <a href="{{ $gymContact->whatsappUrl() }}" target="_blank" rel="noopener" class="btn btn-whatsapp w-full">@icon('whatsapp', '') WhatsApp</a>
        </div>
    </div>
</div>

<main id="main">
    @yield('content')
</main>

{{-- ---------- Footer ---------- --}}
<footer class="relative border-t border-white/10 bg-ink-950 pt-20 pb-10 text-white/65">
    <div class="wrap grid gap-x-10 gap-y-12 sm:grid-cols-2 lg:grid-cols-[minmax(0,22rem)_auto_auto_auto] lg:justify-between lg:gap-x-12">
        <div>
            <a href="{{ url('/') }}" class="flex items-center gap-3">
                <img src="{{ asset('images/logo-sm.webp') }}" alt="" width="48" height="48" loading="lazy" class="size-12 rounded-full ring-1 ring-gold-500/60">
                <span class="font-display text-3xl font-extrabold tracking-wide text-white uppercase">Champion<span class="text-gold-500">Club</span></span>
            </a>
            <p class="mt-5 max-w-sm leading-relaxed">A strength and conditioning gym for people training for their next personal best. Real coaching, real equipment, real results.</p>

            <div class="mt-7 flex flex-wrap gap-3">
                <a href="{{ $gymContact->whatsappUrl() }}" target="_blank" rel="noopener" class="footer-cta-wa btn btn-whatsapp btn-sm">@icon('whatsapp', '') WhatsApp us</a>
                <a href="{{ $gymContact->callUrl() }}" class="footer-cta-call btn btn-ghost btn-sm">@icon('phone', '') Call us</a>
            </div>

            @if(array_filter(config('gym.social')))
                <div class="mt-7 flex gap-3">
                    @foreach(array_filter(config('gym.social')) as $network => $url)
                        <a href="{{ $url }}" target="_blank" rel="noopener" class="inline-flex size-11 items-center justify-center rounded-full border border-white/15 text-lg text-white transition hover:border-gold-500 hover:text-gold-400" aria-label="{{ $siteName }} on {{ ucfirst($network) }}">
                            @icon($network, '')
                        </a>
                    @endforeach
                </div>
            @endif
        </div>

        <div>
            <h2 class="font-sans text-xs font-bold tracking-[0.2em] text-white uppercase">Explore</h2>
            <ul class="mt-5 space-y-3">
                @foreach($nav as $item)
                    @continue($item['route'] === 'gym.contact')
                    <li><a href="{{ route($item['route']) }}" class="transition hover:text-gold-400">{{ $item['label'] }}</a></li>
                @endforeach
            </ul>
        </div>

        <div>
            <h2 class="font-sans text-xs font-bold tracking-[0.2em] text-white uppercase">Visit</h2>
            <ul class="mt-5 space-y-4">
                <li class="flex items-start gap-3">
                    <span class="icon-ring size-9 shrink-0 text-sm">@icon('pin', '')</span>
                    <div>
                        <address class="leading-relaxed not-italic">{{ config('gym.address.street') }}<br>{{ config('gym.address.locality') }}, {{ config('gym.address.region') }}</address>
                        <a href="{{ $gymContact->mapsUrl() }}" target="_blank" rel="noopener" class="link-arrow mt-1 text-sm">Get directions @icon('external', '')</a>
                    </div>
                </li>
                @foreach($gymContact->hours() as $h)
                    <li class="flex items-center gap-3">
                        <span class="icon-ring size-9 shrink-0 text-sm">@icon('clock', '')</span>
                        <div class="leading-tight">
                            <span class="block text-xs font-semibold tracking-wider text-white/45 uppercase">{{ $h['days'] }}</span>
                            <span class="mt-1 block text-white">{{ $h['time'] }}</span>
                        </div>
                    </li>
                @endforeach
            </ul>
        </div>

        <div>
            <h2 class="font-sans text-xs font-bold tracking-[0.2em] text-white uppercase">Contact</h2>
            <ul class="mt-5 space-y-4">
                <li><a href="{{ $gymContact->callUrl() }}" class="flex items-center gap-3 transition hover:text-gold-400"><span class="icon-ring size-9 text-sm">@icon('phone', '')</span>{{ $gymContact->displayPhone() }}</a></li>
                <li><a href="mailto:{{ $gymContact->email() }}" class="flex items-center gap-3 transition hover:text-gold-400"><span class="icon-ring size-9 text-sm">@icon('mail', '')</span>{{ $gymContact->email() }}</a></li>
                <li><a href="{{ $gymContact->whatsappUrl() }}" target="_blank" rel="noopener" class="flex items-center gap-3 transition hover:text-gold-400"><span class="icon-ring size-9 text-sm">@icon('whatsapp', '')</span>Chat on WhatsApp</a></li>
            </ul>
        </div>
    </div>

    <div class="wrap mt-16 flex flex-col gap-3 border-t border-white/10 pt-8 text-xs text-white/40 sm:flex-row sm:items-center sm:justify-between">
        <p>© <span data-year>{{ date('Y') }}</span> {{ $siteName }}. All rights reserved.</p>
    </div>
</footer>

{{-- ---------- Mobile action bar ---------- --}}
<div class="fixed inset-x-0 bottom-0 z-40 grid grid-cols-3 border-t border-white/10 bg-ink-950/95 backdrop-blur-xl lg:hidden">
    <a href="{{ $gymContact->callUrl() }}" class="flex flex-col items-center gap-1 py-2.5 text-[0.72rem] font-semibold text-white">@icon('phone', 'text-lg text-gold-400') Call</a>
    <a href="{{ $gymContact->whatsappUrl() }}" target="_blank" rel="noopener" class="flex flex-col items-center gap-1 border-x border-white/10 py-2.5 text-[0.72rem] font-semibold text-white">@icon('whatsapp', 'text-lg text-[#25d366]') WhatsApp</a>
    <a href="{{ route('gym.trial') }}" class="flex flex-col items-center gap-1 bg-gold-500 py-2.5 text-[0.72rem] font-bold text-ink-950">@icon('bolt', 'text-lg') Free trial</a>
</div>

</body>
</html>
