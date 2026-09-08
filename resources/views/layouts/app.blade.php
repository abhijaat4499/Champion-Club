<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>ChampionClub — Strength & Conditioning Gym</title>
<meta name="description" content="ChampionClub is a strength and conditioning gym for people training for their next personal best. Real coaching, real equipment, real results.">

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Zilla+Slab:wght@400;500;600;700&family=Work+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

<link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>

<a class="skip-link" href="{{ route('gym.home') }}">Skip to content</a>

<header class="site-header" id="top">
  <div class="container header-inner">
    <a class="logo" href="{{ route('gym.home') }}">
   <img src="{{ asset('images/logo.jpg') }}" alt="ChampionClub gym " class="logo-photo-header">
      <span>ChampionClub</span>
    </a>

    <nav class="nav" id="siteNav" aria-label="Primary">
      <ul>
        <li><a href="/program">Programs</a></li>
        <li><a href="/membership">Membership</a></li>
        <li><a href="/trainers">Trainers</a></li>
        <li><a href="/contact">Contact</a></li>
      </ul>
    </nav>

    <div class="header-actions">
      <a class="btn btn-primary btn-small" href="{{ route('gym.contact') }}">Join now</a>
      <button class="nav-toggle" id="navToggle" aria-label="Toggle menu" aria-expanded="false" aria-controls="siteNav">
        <span></span><span></span><span></span>
      </button>
    </div>
  </div>
</header>

<main id="main">

  @yield('content')

</main>

<footer class="site-footer" id="contact">
  <div class="container footer-inner">
    <div class="footer-brand">
      <a class="logo" href="{{ route('gym.home') }}">
        <svg class="logo-mark" viewBox="0 0 48 48" aria-hidden="true">
          <circle cx="24" cy="24" r="21" fill="none"/>
          <path d="M24 12 L27 21 L36 21 L28.5 26.5 L31.5 35.5 L24 30 L16.5 35.5 L19.5 26.5 L12 21 L21 21 Z" fill="currentColor" stroke="none"/>
        </svg>
        <span>ChampionClub</span>
      </a>
      <p>A strength and conditioning gym for people training for their next personal best.</p>
      <div class="social-links">
        <a href="{{ route('gym.contact') }}" aria-label="ChampionClub on Instagram">
          <svg viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="5" fill="none"/><circle cx="12" cy="12" r="4.2" fill="none"/><circle cx="17.2" cy="6.8" r="1" fill="currentColor" stroke="none"/></svg>
        </a>
        <a href="{{ route('gym.contact') }}" aria-label="ChampionClub on Facebook">
          <svg viewBox="0 0 24 24"><path d="M14 9h3V5h-3c-2.2 0-4 1.8-4 4v2H7v4h3v7h4v-7h3l1-4h-4V9c0-.6.4-1 1-1z" fill="currentColor" stroke="none"/></svg>
        </a>
      </div>
    </div>

    <div class="footer-links">
      <h3>Quick links</h3>
      <ul>
        <li><a href="/programs">Programs</a></li>
        <li><a href="/membership">Membership</a></li>
        <li><a href="/trainers">Trainers</a></li>
        <li><a href="{{ route('gym.contact') }}">Free trial</a></li>
      </ul>
    </div>

    <div class="footer-contact">
      <h3>Visit us</h3>
      <address>
        12 Strength Avenue, Sector 21<br>
        Gurugram, Haryana
      </address>
      <p><a href="tel:+919876543210">+91 98765 43210</a></p>
      <p><a href="mailto:hello@championclub.in">hello@championclub.in</a></p>
      <p class="hours">Mon–Sat: 6:00 AM – 10:00 PM<br>Sun: 7:00 AM – 1:00 PM</p>
    </div>
  </div>

  <div class="container footer-bottom">
    <p>© <span id="year"></span> ChampionClub. All rights reserved.</p>
  </div>
</footer>

<script>
  document.getElementById('year').textContent = new Date().getFullYear();
  

  const navToggle = document.getElementById('navToggle');
  const siteNav = document.getElementById('siteNav');

  navToggle.addEventListener('click', () => {
    const isOpen = siteNav.classList.toggle('is-open');
    navToggle.classList.toggle('is-open', isOpen);
    navToggle.setAttribute('aria-expanded', String(isOpen));
  });

  siteNav.querySelectorAll('a').forEach(link => {
    link.addEventListener('click', () => {
      siteNav.classList.remove('is-open');
      navToggle.classList.remove('is-open');
      navToggle.setAttribute('aria-expanded', 'false');
    });
  });

  window.addEventListener('load', () => {
    document.body.classList.add('is-ready');
  });
</script>

</body>
</html>