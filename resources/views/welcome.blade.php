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

<a class="skip-link" href="#main">Skip to content</a>

<header class="site-header" id="top">
  <div class="container header-inner">
    <a class="logo" href="#top">
      <svg class="logo-mark" viewBox="0 0 48 48" aria-hidden="true">
        <circle cx="24" cy="24" r="21" fill="none"/>
        <path d="M24 12 L27 21 L36 21 L28.5 26.5 L31.5 35.5 L24 30 L16.5 35.5 L19.5 26.5 L12 21 L21 21 Z" fill="currentColor" stroke="none"/>
      </svg>
      <span>ChampionClub</span>
    </a>

    <nav class="nav" id="siteNav" aria-label="Primary">
      <ul>
        <li><a href="#programs">Programs</a></li>
        <li><a href="#membership">Membership</a></li>
        <li><a href="#trainers">Trainers</a></li>
        <li><a href="#contact">Contact</a></li>
      </ul>
    </nav>

    <div class="header-actions">
      <a class="btn btn-primary btn-small" href="#trial">Join now</a>
      <button class="nav-toggle" id="navToggle" aria-label="Toggle menu" aria-expanded="false" aria-controls="siteNav">
        <span></span><span></span><span></span>
      </button>
    </div>
  </div>
</header>

<main id="main">

  <section class="hero">
    <div class="container hero-inner">
      <div class="hero-copy">
        <h1>Every champion started as someone who refused to quit.</h1>
        <p class="hero-sub">ChampionClub is a strength and conditioning gym for people training for their next personal best — not someone else's idea of fit. Real coaching, real equipment, real results.</p>
        <div class="hero-actions">
          <a class="btn btn-primary" href="#trial">Start your free trial</a>
          <a class="btn btn-outline" href="#membership">View membership plans</a>
        </div>
        <div class="hero-stats">
          <div class="stat">
            <span class="stat-num">500+</span>
            <span class="stat-label">Members training</span>
          </div>
          <div class="stat">
            <span class="stat-num">12</span>
            <span class="stat-label">Certified coaches</span>
          </div>
          <div class="stat">
            <span class="stat-num">8</span>
            <span class="stat-label">Years running</span>
          </div>
        </div>
      </div>

      <div class="hero-visual" aria-hidden="true">
        <svg class="medallion" viewBox="0 0 400 400">
          <defs>
            <path id="topCurve" d="M 65,200 A 135,135 0 0 1 335,200" fill="none"/>
            <path id="bottomCurve" d="M 90,240 A 135,135 0 0 0 310,240" fill="none"/>
          </defs>
          <circle class="ring-outer" cx="200" cy="200" r="172" fill="none"/>
          <circle class="ring-thin" cx="200" cy="200" r="150" fill="none"/>
          <circle class="ring-main" cx="200" cy="200" r="112" fill="none"/>
          <text class="arc-text">
            <textPath href="#topCurve" startOffset="50%" text-anchor="middle">ChampionClub</textPath>
          </text>
          <text class="arc-text arc-text-small">
            <textPath href="#bottomCurve" startOffset="50%" text-anchor="middle">Strength and Discipline</textPath>
          </text>
          <polygon class="star" points="200,160 209.4,187.06 238.04,187.64 215.22,204.94 223.51,232.36 200,216 176.49,232.36 184.78,204.94 161.96,187.64 190.60,187.06"/>
        </svg>
      </div>
    </div>
  </section>

  <div class="belt-divider" aria-hidden="true"></div>

  <section class="programs" id="programs">
    <div class="container">
      <div class="section-head">
        <h2>Pick a program, then show up.</h2>
        <p>Every program here is coached, not just supervised.</p>
      </div>

      <div class="program-grid">
        <article class="program-card">
          <svg class="icon" viewBox="0 0 48 48"><line x1="6" y1="24" x2="42" y2="24"/><rect x="2" y="18" width="6" height="12" rx="1"/><rect x="40" y="18" width="6" height="12" rx="1"/><rect x="10" y="20" width="4" height="8" rx="1"/><rect x="34" y="20" width="4" height="8" rx="1"/></svg>
          <h3>Strength & Conditioning</h3>
          <p>Barbell fundamentals and progressive overload for real, measurable strength.</p>
        </article>

        <article class="program-card">
          <svg class="icon icon-fill" viewBox="0 0 48 48"><path d="M24 4 L28 18 L42 14 L31 24 L42 34 L28 30 L24 44 L20 30 L6 34 L17 24 L6 14 L20 18 Z"/></svg>
          <h3>Boxing & Combat Fitness</h3>
          <p>Technique-first training that builds elite-level conditioning as a side effect.</p>
        </article>

        <article class="program-card">
          <svg class="icon" viewBox="0 0 48 48"><path d="M17 16c0-5 4-9 7-9s7 4 7 9" fill="none"/><circle cx="24" cy="30" r="12" fill="none"/></svg>
          <h3>Functional Training</h3>
          <p>Movement patterns that carry over to sport, work, and everyday life.</p>
        </article>

        <article class="program-card">
          <svg class="icon" viewBox="0 0 48 48"><circle cx="24" cy="26" r="16" fill="none"/><line x1="24" y1="26" x2="24" y2="16"/><line x1="24" y1="26" x2="31" y2="30"/><line x1="18" y1="6" x2="30" y2="6"/><line x1="24" y1="6" x2="24" y2="10"/></svg>
          <h3>Group HIIT</h3>
          <p>High-intensity interval sessions where the clock is the only opponent.</p>
        </article>

        <article class="program-card">
          <svg class="icon" viewBox="0 0 48 48"><path d="M24 40 C10 34 8 18 24 8 C40 18 38 34 24 40Z" fill="none"/><path d="M24 40 C16 30 16 16 24 8" fill="none"/><path d="M24 40 C32 30 32 16 24 8" fill="none"/></svg>
          <h3>Yoga & Recovery</h3>
          <p>Mobility and breathwork to keep you training without breaking down.</p>
        </article>

        <article class="program-card">
          <svg class="icon" viewBox="0 0 48 48"><rect x="10" y="8" width="28" height="34" rx="2" fill="none"/><rect x="17" y="4" width="14" height="8" rx="2" fill="none"/><line x1="16" y1="20" x2="32" y2="20"/><line x1="16" y1="27" x2="26" y2="27"/><path d="M16 34 l3 3 l6 -6" fill="none"/></svg>
          <h3>Personal Coaching</h3>
          <p>One-on-one programming built entirely around your specific goal.</p>
        </article>
      </div>
    </div>
  </section>

  <section class="standard" id="about">
    <div class="container standard-inner">
      <div class="standard-copy">
        <h2>What training here actually looks like</h2>
        <p>No mirrors selfies, no wasted sets. ChampionClub is built around three things that most gyms only claim to offer.</p>
      </div>

      <div class="standard-list">
        <div class="standard-item">
          <svg class="icon" viewBox="0 0 48 48"><circle cx="24" cy="24" r="18" fill="none"/><circle cx="24" cy="24" r="11" fill="none"/><circle cx="24" cy="24" r="4" fill="currentColor" stroke="none"/></svg>
          <div>
            <h3>Coaches who coach</h3>
            <p>Every session is supervised by a certified trainer who corrects your form, not just your rep count.</p>
          </div>
        </div>

        <div class="standard-item">
          <svg class="icon" viewBox="0 0 48 48"><line x1="14" y1="24" x2="34" y2="24"/><circle cx="10" cy="24" r="6" fill="none"/><circle cx="38" cy="24" r="6" fill="none"/></svg>
          <div>
            <h3>Equipment built for serious training</h3>
            <p>Competition-grade barbells and platforms, maintained on a weekly schedule, not "when someone complains."</p>
          </div>
        </div>

        <div class="standard-item">
          <svg class="icon" viewBox="0 0 48 48"><circle cx="17" cy="16" r="6" fill="none"/><circle cx="31" cy="16" r="6" fill="none"/><path d="M6 40c0-8 6-13 11-13s11 5 11 13" fill="none"/><path d="M20 40c0-8 6-13 11-13s11 5 11 13" fill="none"/></svg>
          <div>
            <h3>A community that pushes you</h3>
            <p>Train alongside people who show up at 6 AM because they want to, not because an app reminded them.</p>
          </div>
        </div>
      </div>
    </div>
  </section>

  <section class="trainers" id="trainers">
    <div class="container">
      <div class="section-head">
        <h2>Coaches who've done the work</h2>
        <p>Every coach at ChampionClub has competed, not just certified.</p>
      </div>

      <div class="trainer-grid">
        <article class="trainer-card">
          <span class="trainer-badge">AR</span>
          <h3>Arjun Rathi</h3>
          <p class="trainer-role">Strength & Powerlifting Coach</p>
          <p class="trainer-bio">Former state-level powerlifter with 9 years of coaching lifters from their first squat to their first meet.</p>
        </article>

        <article class="trainer-card">
          <span class="trainer-badge">MC</span>
          <h3>Meera Chandok</h3>
          <p class="trainer-role">Boxing & Conditioning Coach</p>
          <p class="trainer-bio">Ex-national boxer who now builds conditioning programs that borrow directly from fight camp training.</p>
        </article>

        <article class="trainer-card">
          <span class="trainer-badge">VS</span>
          <h3>Vikram Sethi</h3>
          <p class="trainer-role">Functional Training Coach</p>
          <p class="trainer-bio">Certified CSCS specializing in injury rehab and getting people back to the sport they love.</p>
        </article>
      </div>
    </div>
  </section>

  <section class="membership" id="membership">
    <div class="container">
      <div class="section-head">
        <h2>Membership that matches your intensity</h2>
        <p>No lock-in contracts. Pause or cancel any time.</p>
      </div>

      <div class="podium">
        <div class="tier tier-bronze">
          <span class="tier-medal">Starter</span>
          <p class="tier-price"><span class="amount">₹1,499</span><span class="period">/month</span></p>
          <ul class="tier-features">
            <li>Full gym floor access</li>
            <li>Locker and shower access</li>
            <li>One induction session with a coach</li>
          </ul>
          <a class="btn btn-outline btn-small" href="#trial">Choose Starter</a>
        </div>

        <div class="tier tier-gold featured">
          <span class="tier-flag">Most popular</span>
          <span class="tier-medal">Champion</span>
          <p class="tier-price"><span class="amount">₹2,999</span><span class="period">/month</span></p>
          <ul class="tier-features">
            <li>Everything in Starter</li>
            <li>Unlimited group classes</li>
            <li>Monthly progress check-in</li>
          </ul>
          <a class="btn btn-primary btn-small" href="#trial">Choose Champion</a>
        </div>

        <div class="tier tier-silver">
          <span class="tier-medal">Elite</span>
          <p class="tier-price"><span class="amount">₹4,999</span><span class="period">/month</span></p>
          <ul class="tier-features">
            <li>Everything in Champion</li>
            <li>4 personal coaching sessions</li>
            <li>Custom nutrition plan</li>
          </ul>
          <a class="btn btn-outline btn-small" href="#trial">Choose Elite</a>
        </div>
      </div>
    </div>
  </section>

  <section class="testimonials">
    <div class="container testimonial-inner">
      <blockquote class="quote-main">
        <p>I've trained at four gyms in this city. ChampionClub is the first place where coaches actually watch your form instead of scrolling their phone between sets.</p>
        <cite>Ritika Sinha, member since 2023</cite>
      </blockquote>

      <blockquote class="quote-secondary">
        <p>The 6 AM strength class changed how I think about consistency. Three years in, still haven't missed a Monday.</p>
        <cite>Karan Mehta, member since 2021</cite>
      </blockquote>
    </div>
  </section>

  <section class="final-cta" id="trial">
    <div class="container cta-inner">
      <h2>Your first session is on us.</h2>
      <p>Walk in, meet the coaches, use the floor. No pressure, no sales pitch.</p>
      <a class="btn btn-primary" href="#contact">Book your free trial</a>
    </div>
  </section>

</main>

<footer class="site-footer" id="contact">
  <div class="container footer-inner">
    <div class="footer-brand">
      <a class="logo" href="#top">
        <svg class="logo-mark" viewBox="0 0 48 48" aria-hidden="true">
          <circle cx="24" cy="24" r="21" fill="none"/>
          <path d="M24 12 L27 21 L36 21 L28.5 26.5 L31.5 35.5 L24 30 L16.5 35.5 L19.5 26.5 L12 21 L21 21 Z" fill="currentColor" stroke="none"/>
        </svg>
        <span>ChampionClub</span>
      </a>
      <p>A strength and conditioning gym for people training for their next personal best.</p>
      <div class="social-links">
        <a href="#" aria-label="ChampionClub on Instagram">
          <svg viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="5" fill="none"/><circle cx="12" cy="12" r="4.2" fill="none"/><circle cx="17.2" cy="6.8" r="1" fill="currentColor" stroke="none"/></svg>
        </a>
        <a href="#" aria-label="ChampionClub on Facebook">
          <svg viewBox="0 0 24 24"><path d="M14 9h3V5h-3c-2.2 0-4 1.8-4 4v2H7v4h3v7h4v-7h3l1-4h-4V9c0-.6.4-1 1-1z" fill="currentColor" stroke="none"/></svg>
        </a>
      </div>
    </div>

    <div class="footer-links">
      <h3>Quick links</h3>
      <ul>
        <li><a href="#programs">Programs</a></li>
        <li><a href="#membership">Membership</a></li>
        <li><a href="#trainers">Trainers</a></li>
        <li><a href="#trial">Free trial</a></li>
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