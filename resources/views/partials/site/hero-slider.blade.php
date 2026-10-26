@php
    $total = $heroSlides->count();
@endphp

<section class="hero-slider relative bg-ink-950" aria-roledescription="carousel" aria-label="ChampionClub highlights">
    <div class="swiper">
        <div class="swiper-wrapper">
            @foreach($heroSlides as $slide)
                @php
                    $pattern = $slide->patternName();
                    $points = $slide->pointList();
                    $heading = $loop->first ? 'h1' : 'h2';
                    $headingClass = $pattern === 'card' ? 'display-lg' : 'display-xl';
                @endphp
                <div class="swiper-slide" role="group" aria-roledescription="slide" aria-label="{{ $loop->iteration }} of {{ $total }}">
                    <div class="hero-media absolute inset-0">
                        @if($slide->image)
                            <picture>
                                @if($slide->image_mobile)
                                    <source media="(max-width: 640px)" srcset="{{ asset('storage/' . $slide->image_mobile) }}">
                                @endif
                                <img src="{{ asset('storage/' . $slide->image) }}" alt="" width="1920" height="1080" style="{{ $slide->focus() }}" decoding="async" @if($loop->first) fetchpriority="high" @else loading="lazy" @endif>
                            </picture>
                        @elseif($slide->site_image)
                            @img($slide->site_image, '', $loop->first)
                        @endif
                    </div>

                    @if($pattern === 'card')
                        <div class="absolute inset-0 bg-gradient-to-l from-ink-950/90 via-ink-950/45 to-ink-950/10"></div>
                        <div class="absolute inset-0 bg-gradient-to-t from-ink-950 via-transparent to-ink-950/40"></div>
                    @elseif($pattern === 'center')
                        <div class="absolute inset-0 bg-ink-950/45"></div>
                        <div class="absolute inset-0 bg-gradient-to-t from-ink-950 via-ink-950/30 to-ink-950/50"></div>
                    @else
                        <div class="absolute inset-0 bg-gradient-to-r from-ink-950/95 via-ink-950/60 to-ink-950/10"></div>
                        <div class="absolute inset-0 bg-gradient-to-t from-ink-950 via-transparent to-ink-950/40"></div>
                    @endif

                    <div class="wrap relative flex w-full flex-1 pt-28 pb-44 lg:pt-40 lg:pb-32 {{ $pattern === 'center' ? 'items-end justify-center text-center' : ($pattern === 'card' ? 'items-end justify-end lg:items-center' : 'items-end lg:items-center') }}">
                        <div class="hero-copy {{ $pattern === 'card' ? 'max-w-xl border-l-4 border-gold-500 bg-ink-950/70 p-6 backdrop-blur-md sm:p-10' : ($pattern === 'center' ? 'flex max-w-4xl flex-col items-center' : 'max-w-3xl') }}">
                            @if($slide->label)
                                <p class="eyebrow">{{ $slide->label }}</p>
                            @endif

                            <{{ $heading }} class="{{ $headingClass }} {{ $slide->label ? 'mt-5' : '' }} text-white">{{ $slide->titleHtml() }}</{{ $heading }}>

                            @if($slide->description)
                                <p class="{{ $pattern === 'card' ? 'mt-5 leading-relaxed text-white/75' : 'lead mt-6 max-w-xl text-white/75 max-sm:hidden' }}">{{ $slide->description }}</p>
                            @endif

                            @if($points && $slide->points_style === 'capsules')
                                <ul class="mt-7 flex flex-wrap gap-2.5 text-[0.8rem] font-semibold text-white max-sm:hidden {{ $pattern === 'center' ? 'justify-center' : '' }}">
                                    @foreach($points as $point)
                                        <li class="rounded-full border border-white/25 bg-white/5 px-4 py-2 backdrop-blur">{{ $point }}</li>
                                    @endforeach
                                </ul>
                            @elseif($points && $slide->points_style === 'checks')
                                <ul class="mt-6 grid gap-2.5 text-sm text-white/85 max-sm:hidden {{ $pattern === 'center' ? 'justify-items-center' : '' }}">
                                    @foreach($points as $point)
                                        <li class="flex items-center gap-3">@icon('check', 'text-gold-400') {{ $point }}</li>
                                    @endforeach
                                </ul>
                            @endif

                            @if($slide->buttons())
                                <div class="mt-9 flex flex-wrap gap-3 max-sm:mt-7 max-sm:[&>.btn]:px-4 {{ $pattern === 'center' ? 'justify-center' : '' }}">
                                    @foreach($slide->buttons() as $button)
                                        @php $icon = \App\Models\HeroSlide::targetIcon($button['target']); @endphp
                                        <a href="{{ \App\Models\HeroSlide::targetUrl($button['target']) }}"
                                           class="btn {{ $loop->first ? 'btn-gold' : 'btn-ghost' }}"
                                           @if(\App\Models\HeroSlide::isExternal($button['target'])) target="_blank" rel="noopener" @endif>
                                            @if($icon) @icon($icon, '') @endif
                                            {{ $button['text'] }}
                                            @if(! $icon && $loop->first) @icon('arrow', 'btn-arrow') @endif
                                        </a>
                                    @endforeach
                                </div>
                            @endif

                            @if($pattern === 'spotlight')
                                <a href="{{ $gymContact->callUrl() }}" class="mt-6 inline-flex items-center gap-2 text-sm font-semibold text-white/70 transition hover:text-gold-400 max-sm:hidden">
                                    @icon('phone', 'text-gold-500') Call us: {{ $gymContact->displayPhone() }}
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    @if($total > 1)
        <div class="absolute inset-x-0 bottom-0 z-10 pb-[5.5rem] lg:pb-10">
            <div class="wrap flex items-end gap-5 sm:gap-8">
                <p class="font-display text-white" aria-hidden="true">
                    <span class="text-4xl font-extrabold" data-hero-current>01</span><span class="text-lg text-white/45"> / {{ str_pad($total, 2, '0', STR_PAD_LEFT) }}</span>
                </p>
                <div class="grid flex-1 gap-3 sm:gap-5" style="grid-template-columns: repeat({{ $total }}, minmax(0, 1fr))">
                    @foreach($heroSlides as $slide)
                        <button type="button" class="group text-left" data-hero-go aria-label="Show slide {{ $loop->iteration }}: {{ $slide->tab }}">
                            <span class="mb-2 hidden truncate text-xs font-bold tracking-[0.18em] text-white/55 uppercase transition group-hover:text-white sm:block">{{ $slide->tab }}</span>
                            <span class="hero-bullet block {{ $loop->first ? 'is-active' : '' }}"><span></span></span>
                        </button>
                    @endforeach
                </div>
                <div class="flex items-center gap-2">
                    <button type="button" class="carousel-btn carousel-btn-dark size-11 max-sm:hidden" data-hero-prev aria-label="Previous slide">@icon('arrow', 'rotate-180')</button>
                    <button type="button" class="carousel-btn carousel-btn-dark size-11 max-sm:hidden" data-hero-next aria-label="Next slide">@icon('arrow', '')</button>
                    <button type="button" class="carousel-btn carousel-btn-dark size-11" data-hero-toggle aria-pressed="false" aria-label="Pause slideshow">
                        @icon('pause', '', 'data-icon-pause')@icon('play', '', 'data-icon-play hidden')
                    </button>
                </div>
            </div>
        </div>
    @endif
</section>
