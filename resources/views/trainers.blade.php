@extends('layouts.app')

@section('title', 'Trainers — ChampionClub')

@section('content')

<section class="page-intro">
    <div class="container">
        <h1>Coaches who've done the work</h1>
        <p>Every coach at ChampionClub has competed, not just certified. Here's who you'll actually be training with.</p>
    </div>
</section>

<section class="trainers-detail">
    <div class="container">

        <div class="trainer-row">
            <div class="trainer-visual">
                <div class="trainer-badge-lg">AR</div>
                <span class="trainer-exp">9 years coaching</span>
            </div>
            <div class="trainer-content">
                <h2>Arjun Rathi</h2>
                <p class="trainer-role">Strength & Powerlifting Coach</p>
                <div class="trainer-tags">
                    <span>Powerlifting</span>
                    <span>Barbell Technique</span>
                    <span>Strength Programming</span>
                </div>
                <p class="trainer-bio">Arjun spent six years competing in state-level powerlifting meets before moving into full-time coaching. He specializes in taking lifters from their very first squat to their first competition, with an obsessive focus on bar path and bracing before adding weight.</p>
                <div class="trainer-achievement">
                    <svg class="icon icon-small" viewBox="0 0 48 48"><circle cx="24" cy="20" r="10" fill="none"/><path d="M18 28 L12 42 L24 36 L36 42 L30 28" fill="none"/></svg>
                    <span>Coached 30+ members to their first sanctioned powerlifting meet</span>
                </div>
                <blockquote class="trainer-quote">
                    <p>"Strength is a skill before it's a number. Get the pattern right, and the weight takes care of itself."</p>
                </blockquote>
            </div>
        </div>

        <div class="trainer-row reverse">
            <div class="trainer-visual">
                <div class="trainer-badge-lg">MC</div>
                <span class="trainer-exp">7 years coaching</span>
            </div>
            <div class="trainer-content">
                <h2>Meera Chandok</h2>
                <p class="trainer-role">Boxing & Conditioning Coach</p>
                <div class="trainer-tags">
                    <span>Boxing Technique</span>
                    <span>Fight Conditioning</span>
                    <span>Pad Work</span>
                </div>
                <p class="trainer-bio">Meera represented her state at the national boxing championships before hanging up her competitive gloves. She now runs ChampionClub's boxing and combat fitness program, borrowing conditioning drills straight from fight camp for members who've never thrown a punch.</p>
                <div class="trainer-achievement">
                    <svg class="icon icon-small" viewBox="0 0 48 48"><circle cx="24" cy="20" r="10" fill="none"/><path d="M18 28 L12 42 L24 36 L36 42 L30 28" fill="none"/></svg>
                    <span>Trained 5 members who went on to compete in amateur boxing bouts</span>
                </div>
                <blockquote class="trainer-quote">
                    <p>"You don't need to want to fight. You just need to be willing to work like someone who does."</p>
                </blockquote>
            </div>
        </div>

        <div class="trainer-row">
            <div class="trainer-visual">
                <div class="trainer-badge-lg">VS</div>
                <span class="trainer-exp">11 years coaching</span>
            </div>
            <div class="trainer-content">
                <h2>Vikram Sethi</h2>
                <p class="trainer-role">Functional Training & Rehab Coach</p>
                <div class="trainer-tags">
                    <span>Injury Rehab</span>
                    <span>Mobility</span>
                    <span>Return-to-Sport</span>
                </div>
                <p class="trainer-bio">Vikram's background is in sports rehabilitation, working with athletes recovering from ACL and shoulder injuries before they return to competition. At ChampionClub, he leads functional training sessions designed to build durable, pain-free movement for members of every age and ability.</p>
                <div class="trainer-achievement">
                    <svg class="icon icon-small" viewBox="0 0 48 48"><circle cx="24" cy="20" r="10" fill="none"/><path d="M18 28 L12 42 L24 36 L36 42 L30 28" fill="none"/></svg>
                    <span>Certified CSCS with 11 years in strength and rehab coaching</span>
                </div>
                <blockquote class="trainer-quote">
                    <p>"The best program is the one you can still do in ten years. We train for longevity, not just this Monday."</p>
                </blockquote>
            </div>
        </div>

        <div class="trainer-row reverse">
            <div class="trainer-visual">
                <div class="trainer-badge-lg">PN</div>
                <span class="trainer-exp">6 years coaching</span>
            </div>
            <div class="trainer-content">
                <h2>Priya Nair</h2>
                <p class="trainer-role">Yoga, Recovery & Nutrition Coach</p>
                <div class="trainer-tags">
                    <span>Mobility & Recovery</span>
                    <span>Breathwork</span>
                    <span>Nutrition Coaching</span>
                </div>
                <p class="trainer-bio">Priya combines a 500-hour yoga teacher certification with a sports nutrition coaching background. She runs ChampionClub's recovery sessions and works one-on-one with Elite members to build nutrition plans that actually fit their schedule, not a generic template.</p>
                <div class="trainer-achievement">
                    <svg class="icon icon-small" viewBox="0 0 48 48"><circle cx="24" cy="20" r="10" fill="none"/><path d="M18 28 L12 42 L24 36 L36 42 L30 28" fill="none"/></svg>
                    <span>Built ChampionClub's recovery and mobility curriculum from scratch</span>
                </div>
                <blockquote class="trainer-quote">
                    <p>"Recovery isn't the day off. It's the part of training most people skip, then wonder why they're stuck."</p>
                </blockquote>
            </div>
        </div>

    </div>
</section>

<section class="final-cta">
    <div class="container cta-inner">
        <h2>Train with someone who's been there.</h2>
        <p>Book a free trial and get matched with the coach who fits your goal.</p>
        <a class="btn btn-primary" href="{{ route('gym.contact') }}">Book your free trial</a>
        
    </div>
</section>

@endsection