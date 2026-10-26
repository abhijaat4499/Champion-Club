@props(['title', 'icon' => 'dashboard'])

<header class="dashboard-header">
    <div class="page-heading">
        <span class="page-icon" aria-hidden="true">
            @switch($icon)
                @case('trainers')
                    <svg viewBox="0 0 24 24"><circle cx="12" cy="8" r="3.5"/><path d="M5 20c0-3.9 3.1-7 7-7s7 3.1 7 7"/></svg>
                    @break
                @case('membership')
                    <svg viewBox="0 0 24 24"><rect x="3" y="5.5" width="18" height="13" rx="2.5"/><path d="M3 10h18M7 15h4"/></svg>
                    @break
                @case('offers')
                    <svg viewBox="0 0 24 24"><rect x="3.5" y="9" width="17" height="11.5" rx="2"/><path d="M2.5 9h19v-3h-19zM12 6v14.5"/><path d="M12 6c-1.5-3-5.5-3.5-5.5-1S10 6 12 6zM12 6c1.5-3 5.5-3.5 5.5-1S14 6 12 6z"/></svg>
                    @break
                @case('services')
                    <svg viewBox="0 0 24 24"><path d="M6.5 6.5v11M17.5 6.5v11M3.5 9.5v5M20.5 9.5v5M6.5 12h11"/></svg>
                    @break
                @case('facilities')
                    <svg viewBox="0 0 24 24"><path d="M3.5 20.5h17M5 20.5V9l7-5 7 5v11.5M9.5 20.5v-6h5v6"/></svg>
                    @break
                @case('gallery')
                    <svg viewBox="0 0 24 24"><rect x="3.5" y="4.5" width="17" height="15" rx="2.5"/><circle cx="9" cy="10" r="1.8"/><path d="m4 17 5-4.5 4 3.5 3-2.5 4.5 3.5"/></svg>
                    @break
                @case('videos')
                    <svg viewBox="0 0 24 24"><rect x="3" y="5.5" width="18" height="13" rx="3"/><path d="m10.5 9.5 4 2.5-4 2.5z"/></svg>
                    @break
                @case('homepage')
                    <svg viewBox="0 0 24 24"><path d="M3.5 11 12 4l8.5 7M5.5 9.5V20h13V9.5"/><rect x="8.5" y="12" width="7" height="4.5" rx="1"/></svg>
                    @break
                @case('testimonials')
                    <svg viewBox="0 0 24 24"><path d="M4.5 5.5h15a1.5 1.5 0 0 1 1.5 1.5v9a1.5 1.5 0 0 1-1.5 1.5H10l-4.5 3.5v-3.5h-1A1.5 1.5 0 0 1 3 16V7a1.5 1.5 0 0 1 1.5-1.5z"/><path d="M8 10h8M8 13h5"/></svg>
                    @break
                @case('about')
                    <svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="8.5"/><path d="M12 11v5.5M12 7.6v.1"/></svg>
                    @break
                @case('password')
                    <svg viewBox="0 0 24 24"><rect x="4.5" y="10.5" width="15" height="10" rx="2.5"/><path d="M8 10.5V7.5a4 4 0 0 1 8 0v3"/><circle cx="12" cy="15.5" r="1.3"/></svg>
                    @break
                @default
                    <svg viewBox="0 0 24 24"><rect x="3.5" y="3.5" width="7" height="7" rx="1.5"/><rect x="13.5" y="3.5" width="7" height="7" rx="1.5"/><rect x="3.5" y="13.5" width="7" height="7" rx="1.5"/><rect x="13.5" y="13.5" width="7" height="7" rx="1.5"/></svg>
            @endswitch
        </span>
        <h1>{{ $title }}</h1>
    </div>

    @if($slot->isNotEmpty())
        <div class="page-actions">{{ $slot }}</div>
    @endif
</header>
