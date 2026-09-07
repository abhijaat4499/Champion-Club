@extends('layouts.app')

@section('title', 'Contact Us — ChampionClub')

@section('content')

<section class="page-intro">
    <div class="container">
        <h1>Let's talk training</h1>
        <p>Questions about membership, programs, or just want to see the floor first? Reach out, or walk in during class hours.</p>
    </div>
</section>

<section class="contact-section">
    <div class="container contact-inner">

        <form class="contact-form" action="{{ route('store.contact') }}" method="POST">
            @csrf
            <h2>Send a message</h2>

            @if(session('success'))
                <div class="alert-success">{{ session('success') }}</div>
            @endif

            <div class="form-grid">
                <div class="form-row">
                    <label for="name">Name</label>
                    <input type="text" id="name" name="name" value="{{ old('name') }}" placeholder="Your full name" required>
                    @error('name') <span class="form-error">{{ $message }}</span> @enderror
                </div>

                <div class="form-row">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" placeholder="you@example.com" required>
                    @error('email') <span class="form-error">{{ $message }}</span> @enderror
                </div>

                <div class="form-row">
                    <label for="phone">Phone</label>
                    <input type="tel" id="phone" name="phone" value="{{ old('phone') }}" inputmode="numeric" maxlength="10" placeholder="99889 76767">
 @error('phone') <span class="form-error">{{ $message }}</span> @enderror                </div>

                <div class="form-row">
                    <label for="interest">I'm interested in</label>
                    <select id="interest" name="interest">
                        <option value="" disabled {{ old('interest') ? '' : 'selected' }}>Select an option</option>
                        <option value="trial" {{ old('interest') == 'trial' ? 'selected' : '' }}>Free trial</option>
                        <option value="membership" {{ old('interest') == 'membership' ? 'selected' : '' }}>Membership plans</option>
                        <option value="coaching" {{ old('interest') == 'coaching' ? 'selected' : '' }}>Personal coaching</option>
                        <option value="other" {{ old('interest') == 'other' ? 'selected' : '' }}>Something else</option>
                    </select>
                </div>

                <div class="form-row form-row-full">
                    <label for="message">Message</label>
                    <textarea id="message" name="message" rows="5" placeholder="Tell us what you're looking for" required>{{ old('message') }}</textarea>
                    @error('message') <span class="form-error">{{ $message }}</span> @enderror
                </div>
            </div>

            <button type="submit" class="btn btn-primary">Send message</button>
        </form>

        <div class="contact-details">
            <div class="contact-block">
                <h3>Visit us</h3>
                <address>12 Strength Avenue, Sector 21<br>Gurugram, Haryana</address>
            </div>
            <div class="contact-block">
                <h3>Call or email</h3>
                <p><a href="tel:+919876543210">+91 98765 43210</a></p>
                <p><a href="mailto:hello@championclub.in">hello@championclub.in</a></p>
            </div>
            <div class="contact-block">
                <h3>Hours</h3>
                <p class="hours">Mon–Sat: 6:00 AM – 10:00 PM<br>Sun: 7:00 AM – 1:00 PM</p>
            </div>
        </div>

    </div>
    <script>
    const phoneInput = document.getElementById('phone');
    phoneInput.addEventListener('input', function () {
        this.value = this.value.replace(/[^0-9]/g, '').slice(0, 10);
    });
</script>


@endsection