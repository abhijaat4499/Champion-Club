@extends('layouts.app')

@section('title', 'Membership — ChampionClub')

@section('content')

<section class="page-intro">
    <div class="container">
        <h1>Membership that matches your intensity</h1>
        <p>No lock-in contracts. Pause or cancel any time. Pick the plan that fits where you are right now — you can always change it later.</p>
    </div>
</section>

<section class="membership" id="plans">
    <div class="container">
        <div class="podium">
            <div class="tier tier-bronze">
                <span class="tier-medal">Starter</span>
                <p class="tier-price"><span class="amount">₹1,499</span><span class="period">/month</span></p>
                <ul class="tier-features">
                    <li>Full gym floor access</li>
                    <li>Locker and shower access</li>
                    <li>One induction session with a coach</li>
                </ul>
                <a class="btn btn-outline btn-small" href="{{ route('gym.contact') }}">Choose Starter</a>
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
                <a class="btn btn-primary btn-small" href="{{ route('gym.contact') }}">Choose Champion</a>
            </div>

            <div class="tier tier-silver">
                <span class="tier-medal">Elite</span>
                <p class="tier-price"><span class="amount">₹4,999</span><span class="period">/month</span></p>
                <ul class="tier-features">
                    <li>Everything in Champion</li>
                    <li>4 personal coaching sessions</li>
                    <li>Custom nutrition plan</li>
                </ul>
                <a class="btn btn-outline btn-small" href="{{ route('gym.contact') }}">Choose Elite</a>
            </div>
        </div>
    </div>
</section>

<section class="compare-section">
    <div class="container">
        <div class="section-head">
            <h2>Compare every plan</h2>
            <p>Every plan includes full gym floor access — here's exactly what changes as you go up.</p>
        </div>

        <div class="compare-table-wrap">
            <table class="compare-table">
                <thead>
                    <tr>
                        <th scope="col">Feature</th>
                        <th scope="col">Starter</th>
                        <th scope="col" class="highlight-col">Champion</th>
                        <th scope="col">Elite</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <th scope="row">Gym floor access</th>
                        <td><span class="tick">✓</span></td>
                        <td class="highlight-col"><span class="tick">✓</span></td>
                        <td><span class="tick">✓</span></td>
                    </tr>
                    <tr>
                        <th scope="row">Locker & shower access</th>
                        <td><span class="tick">✓</span></td>
                        <td class="highlight-col"><span class="tick">✓</span></td>
                        <td><span class="tick">✓</span></td>
                    </tr>
                    <tr>
                        <th scope="row">Induction session</th>
                        <td>1 session</td>
                        <td class="highlight-col">1 session</td>
                        <td>1 session</td>
                    </tr>
                    <tr>
                        <th scope="row">Group classes</th>
                        <td><span class="dash">—</span></td>
                        <td class="highlight-col">Unlimited</td>
                        <td>Unlimited</td>
                    </tr>
                    <tr>
                        <th scope="row">Monthly progress check-in</th>
                        <td><span class="dash">—</span></td>
                        <td class="highlight-col"><span class="tick">✓</span></td>
                        <td><span class="tick">✓</span></td>
                    </tr>
                    <tr>
                        <th scope="row">Personal coaching</th>
                        <td><span class="dash">—</span></td>
                        <td class="highlight-col"><span class="dash">—</span></td>
                        <td>4 sessions/mo</td>
                    </tr>
                    <tr>
                        <th scope="row">Custom nutrition plan</th>
                        <td><span class="dash">—</span></td>
                        <td class="highlight-col"><span class="dash">—</span></td>
                        <td><span class="tick">✓</span></td>
                    </tr>
                    <tr>
                        <th scope="row">Guest passes</th>
                        <td>0 / month</td>
                        <td class="highlight-col">1 / month</td>
                        <td>3 / month</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</section>

<section class="faq-section">
    <div class="container">
        <div class="section-head">
            <h2>Membership FAQs</h2>
            <p>Still deciding? Here's what members ask most before signing up.</p>
        </div>

        <div class="faq-list">
            <details class="faq-item">
                <summary>Is there a joining fee?</summary>
                <p>No hidden joining fee. The price you see on each plan is exactly what you pay, billed monthly.</p>
            </details>

            <details class="faq-item">
                <summary>Can I freeze my membership?</summary>
                <p>Yes — every plan includes one free freeze per quarter, for up to two weeks, for travel or medical reasons.</p>
            </details>

            <details class="faq-item">
                <summary>Can I switch plans later?</summary>
                <p>Absolutely. Upgrade or downgrade any time from the front desk — the change applies from your next billing cycle.</p>
            </details>

            <details class="faq-item">
                <summary>What if I want to cancel?</summary>
                <p>There's no lock-in contract on any plan. Cancel any time, no cancellation fee.</p>
            </details>

            <details class="faq-item">
                <summary>Do you offer student or corporate discounts?</summary>
                <p>Yes — ask the front desk about current student and corporate group offers.</p>
            </details>
        </div>
    </div>
</section>

<section class="final-cta">
    <div class="container cta-inner">
        <h2>Still not sure which plan fits?</h2>
        <p>Walk in, meet the coaches, and we'll help you pick — no pressure, no sales pitch.</p>
        <a class="btn btn-primary" href="{{ route('gym.contact') }}">Talk to a coach</a>
    </div>
</section>

@endsection