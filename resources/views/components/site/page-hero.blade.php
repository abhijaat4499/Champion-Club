@props(['image', 'eyebrow' => null, 'title', 'crumb' => null])

<section class="relative isolate flex min-h-[62svh] items-end overflow-hidden bg-ink-950 pt-36 pb-14 sm:min-h-[66svh] lg:min-h-[72vh] lg:pb-20">
    @img($image, 'absolute inset-0 -z-20 size-full object-cover animate-ken-burns', true)
    <div class="absolute inset-0 -z-10 bg-gradient-to-t from-ink-950 via-ink-950/70 to-ink-950/35"></div>
    <div class="absolute inset-y-0 left-0 -z-10 w-2/3 bg-gradient-to-r from-ink-950/80 to-transparent"></div>

    <div class="wrap">
        @if($crumb)
            <nav aria-label="Breadcrumb" class="mb-6 text-xs font-semibold tracking-[0.18em] text-white/55 uppercase">
                <ol class="flex items-center gap-2">
                    <li><a href="{{ url('/') }}" class="transition hover:text-gold-400">Home</a></li>
                    <li aria-hidden="true">/</li>
                    <li aria-current="page" class="text-gold-400">{{ $crumb }}</li>
                </ol>
            </nav>
        @endif

        @if($eyebrow)
            <p class="eyebrow">{{ $eyebrow }}</p>
        @endif

        <h1 class="display-xl mt-4 max-w-4xl text-white">{{ $title }}</h1>

        @if($slot->isNotEmpty())
            <div class="lead mt-6 max-w-2xl text-white/75">{{ $slot }}</div>
        @endif

        @isset($actions)
            <div class="mt-9 flex flex-wrap gap-3">{{ $actions }}</div>
        @endisset
    </div>
</section>
