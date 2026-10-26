<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>@yield('title', 'Admin') — ChampionClub</title>
<link href="https://fonts.googleapis.com/css2?family=Zilla+Slab:wght@600;700&family=Work+Sans:wght@400;500;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('css/admin.css') }}">
</head>
<body class="admin-panel">

{{-- ---------- Top navbar ---------- --}}
<header class="admin-navbar">
    <div class="admin-navbar-left">
        <button type="button" class="sidebar-toggle" id="sidebarToggle" aria-label="Toggle menu" aria-controls="adminSidebar" aria-expanded="false">
            <span></span><span></span><span></span>
        </button>
        <a href="{{ route('admin.dashboard') }}" class="admin-logo">
            <img src="{{ asset('images/logo-admin.webp') }}" alt="" width="38" height="38" class="admin-logo-img">
            <span>ChampionClub</span>
        </a>
    </div>

    <form method="POST" action="{{ route('admin.logout') }}">
        @csrf
        <button type="submit" class="btn-logout">Logout</button>
    </form>
</header>

<div class="admin-shell">

    {{-- ---------- Left sidebar ---------- --}}
    <aside class="admin-sidebar" id="adminSidebar">
        <nav aria-label="Admin">
            <a href="{{ route('admin.dashboard') }}" class="side-link {{ request()->routeIs('admin.dashboard') ? 'is-active' : '' }}">
                <svg viewBox="0 0 24 24" aria-hidden="true"><rect x="3.5" y="3.5" width="7" height="7" rx="1.5"/><rect x="13.5" y="3.5" width="7" height="7" rx="1.5"/><rect x="3.5" y="13.5" width="7" height="7" rx="1.5"/><rect x="13.5" y="13.5" width="7" height="7" rx="1.5"/></svg>
                Dashboard
            </a>

            <a href="{{ route('admin.hero.index') }}" class="side-link {{ request()->routeIs('admin.hero.*') ? 'is-active' : '' }}">
                <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M3.5 11 12 4l8.5 7M5.5 9.5V20h13V9.5"/><rect x="8.5" y="12" width="7" height="4.5" rx="1"/></svg>
                Homepage
            </a>

            <a href="{{ route('admin.trainers.index') }}" class="side-link {{ request()->routeIs('admin.trainers.*') ? 'is-active' : '' }}">
                <svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="12" cy="8" r="3.5"/><path d="M5 20c0-3.9 3.1-7 7-7s7 3.1 7 7"/></svg>
                Trainers
            </a>

            <a href="{{ route('admin.plans.index') }}" class="side-link {{ request()->routeIs('admin.plans.*') ? 'is-active' : '' }}">
                <svg viewBox="0 0 24 24" aria-hidden="true"><rect x="3" y="5.5" width="18" height="13" rx="2.5"/><path d="M3 10h18M7 15h4"/></svg>
                Membership
            </a>

            <a href="{{ route('admin.offers.index') }}" class="side-link {{ request()->routeIs('admin.offers.*') ? 'is-active' : '' }}">
                <svg viewBox="0 0 24 24" aria-hidden="true"><rect x="3.5" y="9" width="17" height="11.5" rx="2"/><path d="M2.5 9h19v-3h-19zM12 6v14.5"/><path d="M12 6c-1.5-3-5.5-3.5-5.5-1S10 6 12 6zM12 6c1.5-3 5.5-3.5 5.5-1S14 6 12 6z"/></svg>
                Offers
            </a>

            <a href="{{ route('admin.services.index') }}" class="side-link {{ request()->routeIs('admin.services.*') ? 'is-active' : '' }}">
                <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M6.5 6.5v11M17.5 6.5v11M3.5 9.5v5M20.5 9.5v5M6.5 12h11"/></svg>
                Services
            </a>

            <a href="{{ route('admin.facilities.index') }}" class="side-link {{ request()->routeIs('admin.facilities.*') ? 'is-active' : '' }}">
                <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M3.5 20.5h17M5 20.5V9l7-5 7 5v11.5M9.5 20.5v-6h5v6"/></svg>
                Facilities
            </a>

            <a href="{{ route('admin.gallery.index') }}" class="side-link {{ request()->routeIs('admin.gallery.*') ? 'is-active' : '' }}">
                <svg viewBox="0 0 24 24" aria-hidden="true"><rect x="3.5" y="4.5" width="17" height="15" rx="2.5"/><circle cx="9" cy="10" r="1.8"/><path d="m4 17 5-4.5 4 3.5 3-2.5 4.5 3.5"/></svg>
                Gallery
            </a>

            <a href="{{ route('admin.testimonials.index') }}" class="side-link {{ request()->routeIs('admin.testimonials.*', 'admin.videos.*') ? 'is-active' : '' }}">
                <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M4.5 5.5h15a1.5 1.5 0 0 1 1.5 1.5v9a1.5 1.5 0 0 1-1.5 1.5H10l-4.5 3.5v-3.5h-1A1.5 1.5 0 0 1 3 16V7a1.5 1.5 0 0 1 1.5-1.5z"/><path d="M8 10h8M8 13h5"/></svg>
                Testimonials
                @php $pendingReviews = \App\Models\Review::pending()->count(); @endphp
                @if($pendingReviews)
                    <span class="count-pill" title="New reviews waiting for approval">{{ $pendingReviews }}</span>
                @endif
            </a>

            <a href="{{ route('admin.site.edit') }}" class="side-link {{ request()->routeIs('admin.site.*', 'admin.about-sections.*') ? 'is-active' : '' }}">
                <svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="12" cy="12" r="8.5"/><path d="M12 11v5.5M12 7.6v.1"/></svg>
                About &amp; Contact
            </a>

            {{-- Settings --}}
            <details class="side-group" {{ request()->routeIs('admin.password.change*') ? 'open' : '' }}>
                <summary class="side-link {{ request()->routeIs('admin.password.change*') ? 'is-active' : '' }}">
                    <svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.7 1.7 0 0 0 .3 1.8l.1.1a2 2 0 1 1-2.8 2.8l-.1-.1a1.7 1.7 0 0 0-1.8-.3 1.7 1.7 0 0 0-1 1.5V21a2 2 0 1 1-4 0v-.1a1.7 1.7 0 0 0-1.1-1.5 1.7 1.7 0 0 0-1.8.3l-.1.1a2 2 0 1 1-2.8-2.8l.1-.1a1.7 1.7 0 0 0 .3-1.8 1.7 1.7 0 0 0-1.5-1H3a2 2 0 1 1 0-4h.1a1.7 1.7 0 0 0 1.5-1.1 1.7 1.7 0 0 0-.3-1.8l-.1-.1a2 2 0 1 1 2.8-2.8l.1.1a1.7 1.7 0 0 0 1.8.3H9a1.7 1.7 0 0 0 1-1.5V3a2 2 0 1 1 4 0v.1a1.7 1.7 0 0 0 1 1.5 1.7 1.7 0 0 0 1.8-.3l.1-.1a2 2 0 1 1 2.8 2.8l-.1.1a1.7 1.7 0 0 0-.3 1.8V9a1.7 1.7 0 0 0 1.5 1H21a2 2 0 1 1 0 4h-.1a1.7 1.7 0 0 0-1.5 1z"/></svg>
                    Settings
                    <svg class="side-chevron" viewBox="0 0 24 24" aria-hidden="true"><path d="m9 6 6 6-6 6"/></svg>
                </summary>

                <div class="side-sub">
                    <a href="{{ route('admin.password.change') }}" class="side-sublink {{ request()->routeIs('admin.password.change*') ? 'is-active' : '' }}">Change password</a>
                    <form method="POST" action="{{ route('admin.logout') }}">
                        @csrf
                        <button type="submit" class="side-sublink side-logout">Logout</button>
                    </form>
                </div>
            </details>
        </nav>
    </aside>

    <main class="admin-main">
        @error('upload')
            <div class="alert-error">{{ $message }}</div>
        @enderror
        @yield('content')
    </main>
</div>

<script>
    const sidebarToggle = document.getElementById('sidebarToggle');
    sidebarToggle.addEventListener('click', () => {
        const isOpen = document.body.classList.toggle('sidebar-open');
        sidebarToggle.setAttribute('aria-expanded', String(isOpen));
    });
</script>
@include('admin.partials.busy-forms')
</body>
</html>
