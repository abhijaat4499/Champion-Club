@extends('layouts.app')

@section('title', 'Programs — ChampionClub')

@section('content')

<section class="page-intro">
    <div class="container">
        <h1>Six ways to train hard</h1>
        <p>Every program is coached, not just supervised. Pick one, or mix a few once you know what you like.</p>
    </div>
</section>

<section class="program-detail-list">
    <div class="container">

        <div class="program-detail">
            <div class="program-detail-icon">
                <svg class="icon" viewBox="0 0 48 48"><line x1="6" y1="24" x2="42" y2="24"/><rect x="2" y="18" width="6" height="12" rx="1"/><rect x="40" y="18" width="6" height="12" rx="1"/><rect x="10" y="20" width="4" height="8" rx="1"/><rect x="34" y="20" width="4" height="8" rx="1"/></svg>
            </div>
            <div class="program-detail-body">
                <h2>Strength & Conditioning</h2>
                <p>Barbell fundamentals and progressive overload for real, measurable strength — built around the squat, bench, and deadlift.</p>
                <ul class="program-highlights">
                    <li>Beginner to advanced tracks</li>
                    <li>Technique work every session</li>
                    <li>Progress tracked every 4 weeks</li>
                </ul>
            </div>
        </div>

        <div class="program-detail">
            <div class="program-detail-icon">
                <svg class="icon icon-fill" viewBox="0 0 48 48"><path d="M24 4 L28 18 L42 14 L31 24 L42 34 L28 30 L24 44 L20 30 L6 34 L17 24 L6 14 L20 18 Z"/></svg>
            </div>
            <div class="program-detail-body">
                <h2>Boxing & Combat Fitness</h2>
                <p>Technique-first training that builds elite-level conditioning as a side effect — no fighting experience required.</p>
                <ul class="program-highlights">
                    <li>No experience needed</li>
                    <li>Pad work and bag rounds</li>
                    <li>Full-body conditioning built in</li>
                </ul>
            </div>
        </div>

        <div class="program-detail">
            <div class="program-detail-icon">
                <svg class="icon" viewBox="0 0 48 48"><path d="M17 16c0-5 4-9 7-9s7 4 7 9" fill="none"/><circle cx="24" cy="30" r="12" fill="none"/></svg>
            </div>
            <div class="program-detail-body">
                <h2>Functional Training</h2>
                <p>Movement patterns that carry over to sport, work, and everyday life — kettlebells, sleds, and sandbags over machines.</p>
                <ul class="program-highlights">
                    <li>Focus on mobility and control</li>
                    <li>Great for injury prevention</li>
                    <li>Scales to any fitness level</li>
                </ul>
            </div>
        </div>

        <div class="program-detail">
            <div class="program-detail-icon">
                <svg class="icon" viewBox="0 0 48 48"><circle cx="24" cy="26" r="16" fill="none"/><line x1="24" y1="26" x2="24" y2="16"/><line x1="24" y1="26" x2="31" y2="30"/><line x1="18" y1="6" x2="30" y2="6"/><line x1="24" y1="6" x2="24" y2="10"/></svg>
            </div>
            <div class="program-detail-body">
                <h2>Group HIIT</h2>
                <p>High-intensity interval sessions where the clock is the only opponent — a new format every week so it never gets stale.</p>
                <ul class="program-highlights">
                    <li>45-minute sessions</li>
                    <li>New format every week</li>
                    <li>Runs three times daily on weekdays</li>
                </ul>
            </div>
        </div>

        <div class="program-detail">
            <div class="program-detail-icon">
                <svg class="icon" viewBox="0 0 48 48"><path d="M24 40 C10 34 8 18 24 8 C40 18 38 34 24 40Z" fill="none"/><path d="M24 40 C16 30 16 16 24 8" fill="none"/><path d="M24 40 C32 30 32 16 24 8" fill="none"/></svg>
            </div>
            <div class="program-detail-body">
                <h2>Yoga & Recovery</h2>
                <p>Mobility and breathwork to keep you training without breaking down — open to every membership tier.</p>
                <ul class="program-highlights">
                    <li>Evening slots, five days a week</li>
                    <li>Focused on active recovery</li>
                    <li>Open to all membership tiers</li>
                </ul>
            </div>
        </div>

        <div class="program-detail">
            <div class="program-detail-icon">
                <svg class="icon" viewBox="0 0 48 48"><rect x="10" y="8" width="28" height="34" rx="2" fill="none"/><rect x="17" y="4" width="14" height="8" rx="2" fill="none"/><line x1="16" y1="20" x2="32" y2="20"/><line x1="16" y1="27" x2="26" y2="27"/><path d="M16 34 l3 3 l6 -6" fill="none"/></svg>
            </div>
            <div class="program-detail-body">
                <h2>Personal Coaching</h2>
                <p>One-on-one programming built entirely around your specific goal, with weekly check-ins to adjust as you progress.</p>
                <ul class="program-highlights">
                    <li>Custom program design</li>
                    <li>Weekly check-ins</li>
                    <li>Included with Champion and Elite plans</li>
                </ul>
            </div>
        </div>

    </div>
</section>

<section class="final-cta">
    <div class="container cta-inner">
        <h2>Not sure which program fits?</h2>
        <p>Book a free trial and a coach will help you pick the right one.</p>
        <a class="btn btn-primary" href="{{ route('gym.contact') }}">Book your free trial</a>
    </div>
</section>

@endsection