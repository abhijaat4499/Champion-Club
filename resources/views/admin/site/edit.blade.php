@extends('layouts.admin-panel')

@section('title', 'About & Contact')

@php
    $hours = old('hours', config('gym.hours'));
    $highlights = old('highlights', config('gym.highlights'));
@endphp

@section('content')
<div class="dashboard-wrap">

    <x-admin.page-header title="About & Contact" icon="about">
        <a href="{{ route('gym.about') }}" class="btn-logout" target="_blank" rel="noopener">View on website</a>
    </x-admin.page-header>

    @if(session('success'))
        <div class="alert-success">{{ session('success') }}</div>
    @endif

    @if($errors->any())
        <div class="alert-error settings-errors">
            <strong>Please fix these and save again:</strong>
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('admin.site.update') }}" class="site-form" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        {{-- ---------- Contact ---------- --}}
        <section class="settings-card">
            <h2>Contact details</h2>
            <p class="settings-note">Used in the header, footer, call and WhatsApp buttons across the website.</p>

            <div class="form-row">
                <label for="phone">Phone number</label>
                <input type="tel" id="phone" name="phone" value="{{ old('phone', preg_replace('/\D/', '', config('gym.phone'))) }}" inputmode="numeric" maxlength="10" required>
                <small>10-digit mobile number, without +91 or 0.</small>
            </div>

            <div class="form-row">
                <label for="whatsapp">WhatsApp number</label>
                <input type="tel" id="whatsapp" name="whatsapp" value="{{ old('whatsapp', preg_replace('/\D/', '', config('gym.whatsapp'))) }}" inputmode="numeric" maxlength="10" required>
                <small>10-digit number that receives WhatsApp messages.</small>
            </div>

            <div class="form-row">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" value="{{ old('email', config('gym.email')) }}" required>
            </div>

            <div class="form-row">
                <label for="whatsapp_message">Default WhatsApp message</label>
                <input type="text" id="whatsapp_message" name="whatsapp_message" value="{{ old('whatsapp_message', config('gym.whatsapp_message')) }}" maxlength="200" required>
            </div>
        </section>

        {{-- ---------- Address ---------- --}}
        <section class="settings-card">
            <h2>Address</h2>
            <p class="settings-note">The Google map and "Get directions" links use this address.</p>

            <div class="form-row span-2">
                <label for="street">Street address</label>
                <input type="text" id="street" name="address[street]" value="{{ old('address.street', config('gym.address.street')) }}" maxlength="200" required>
            </div>

            <div class="form-row">
                <label for="locality">City</label>
                <input type="text" id="locality" name="address[locality]" value="{{ old('address.locality', config('gym.address.locality')) }}" maxlength="80" required>
            </div>

            <div class="form-row">
                <label for="region">State</label>
                <input type="text" id="region" name="address[region]" value="{{ old('address.region', config('gym.address.region')) }}" maxlength="80" required>
            </div>

            <div class="form-row span-2">
                <label for="maps_url">Google Maps link <small>(optional)</small></label>
                <input type="url" id="maps_url" name="address[maps_url]" value="{{ old('address.maps_url', config('gym.address.maps_url')) }}" maxlength="600" placeholder="https://maps.app.goo.gl/...">
                <small>Open your gym on Google Maps, tap Share → Copy link, and paste it here. "Get directions" and "Visit us" buttons will open this exact place. Leave empty to search by the address above.</small>
                @error('address.maps_url') <div class="alert-error">{{ $message }}</div> @enderror
            </div>
        </section>

        {{-- ---------- Opening hours ---------- --}}
        <section class="settings-card">
            <h2>Opening hours</h2>
            <p class="settings-note">Add one row for each set of days with the same timing.</p>

            <div class="repeat-list" id="hoursList">
                @foreach($hours as $i => $row)
                    @include('admin.site._hours-row', ['i' => $i, 'row' => $row])
                @endforeach
            </div>
            <button type="button" class="btn-row-add" data-add-row="hoursList">+ Add timing row</button>

            <template id="hoursListTemplate">
                @include('admin.site._hours-row', ['i' => '__i__', 'row' => ['from' => 'Mo', 'to' => 'Fr', 'opens' => '', 'closes' => '']])
            </template>
        </section>

        {{-- ---------- Social ---------- --}}
        <section class="settings-card">
            <h2>Social media</h2>
            <p class="settings-note">Leave empty to hide the icon on the website.</p>

            <div class="form-row">
                <label for="instagram">Instagram link <small>(optional)</small></label>
                <input type="url" id="instagram" name="social[instagram]" value="{{ old('social.instagram', config('gym.social.instagram')) }}" placeholder="https://instagram.com/yourgym">
            </div>

            <div class="form-row">
                <label for="facebook">Facebook link <small>(optional)</small></label>
                <input type="url" id="facebook" name="social[facebook]" value="{{ old('social.facebook', config('gym.social.facebook')) }}" placeholder="https://facebook.com/yourgym">
            </div>
        </section>

        {{-- ---------- About page ---------- --}}
        <section class="settings-card" id="about-gym">
            <div class="settings-card-head">
                <div>
                    <h2>About the gym</h2>
                    <p class="settings-note">The "Our approach" section of the About page, plus any extra sections you add below it.</p>
                </div>
                <a href="{{ route('admin.about-sections.create') }}" class="btn-admin-small">+ Add section</a>
            </div>

            <div class="form-row span-2">
                <label for="about_title">Heading</label>
                <input type="text" id="about_title" name="about_title" value="{{ old('about_title', config('gym.about_title')) }}" maxlength="150" required>
            </div>

            <div class="form-row span-2">
                <label for="about_story">Description</label>
                <textarea id="about_story" name="about_story" rows="8" maxlength="5000" required>{{ old('about_story', $story) }}</textarea>
                <small>Leave an empty line between paragraphs.</small>
            </div>

            <div class="form-row">
                <label for="years_running">Years running</label>
                <input type="number" id="years_running" name="years_running" min="0" value="{{ old('years_running', config('gym.years_running')) }}" required>
                <small>Shown in the "years coaching" box next to the photo.</small>
            </div>

            @php $photo = \App\Support\SiteSettings::aboutImage('main'); @endphp
            <div class="form-row about-photo-field">
                <label for="about_image_main">Photo <small>(optional)</small></label>
                @if($photo && isset($photo['url']))
                    <img src="{{ $photo['url'] }}" alt="" class="about-photo-preview">
                @elseif($photo)
                    <img src="{{ asset(config('site-images.' . $photo['site'] . '.sm')) }}" alt="" class="about-photo-preview">
                @else
                    <span class="about-photo-preview about-photo-hidden">No photo</span>
                @endif
                <input type="file" id="about_image_main" name="about_image_main" accept="image/jpeg,image/png,image/webp">
                <select name="about_image_main_mode" aria-label="Photo option">
                    <option value="keep">Keep current photo</option>
                    <option value="default">Use default photo</option>
                    <option value="hide">No photo</option>
                </select>
                <small>A new upload replaces the current photo. {{ \App\Support\ImageUpload::HINT }}</small>
                @error('about_image_main') <div class="alert-error">{{ $message }}</div> @enderror
            </div>

            <div class="about-sections-list">
                <h3>Extra sections</h3>
                @forelse($aboutSections as $section)
                    <div class="about-section-row">
                        @if($section->image)
                            <img src="{{ $section->imageUrl() }}" alt="" class="service-thumb" style="{{ $section->imageFocus() }}">
                        @else
                            <span class="service-thumb service-thumb-empty">{{ mb_strtoupper(mb_substr($section->title, 0, 1)) }}</span>
                        @endif
                        <div class="about-section-text">
                            <span class="person-name">{{ $section->title }}</span>
                            <span class="person-sub">{{ \Illuminate\Support\Str::limit($section->body, 80) }}</span>
                        </div>
                        <span class="status-chip status-{{ $section->is_active ? 'live' : 'hidden' }}">{{ $section->is_active ? 'Live' : 'Hidden' }}</span>
                        <div class="row-actions">
                            <a href="{{ route('admin.about-sections.edit', $section) }}" class="btn-edit">Edit</a>
                            <button type="submit" form="delete-section-{{ $section->id }}" class="btn-delete">Delete</button>
                        </div>
                    </div>
                @empty
                    <p class="about-sections-empty">No extra sections yet. Use "+ Add section" to share news, changes or anything new on the About page.</p>
                @endforelse
            </div>
        </section>

        {{-- ---------- Stats ---------- --}}
        <section class="settings-card">
            <h2>Gym stats</h2>
            <p class="settings-note">The four numbers shown under the home page hero and on the About page.</p>

            <div class="repeat-list">
                @foreach($highlights as $i => $h)
                    <div class="repeat-row stat-row">
                        <div class="form-row">
                            <label for="hl-{{ $i }}-value">Number</label>
                            <input type="number" id="hl-{{ $i }}-value" name="highlights[{{ $i }}][value]" min="0" value="{{ $h['value'] ?? '' }}" required>
                        </div>
                        <div class="form-row">
                            <label for="hl-{{ $i }}-suffix">After number</label>
                            <input type="text" id="hl-{{ $i }}-suffix" name="highlights[{{ $i }}][suffix]" maxlength="3" value="{{ $h['suffix'] ?? '' }}" placeholder="e.g. +">
                        </div>
                        <div class="form-row">
                            <label for="hl-{{ $i }}-label">Label</label>
                            <input type="text" id="hl-{{ $i }}-label" name="highlights[{{ $i }}][label]" maxlength="40" value="{{ $h['label'] ?? '' }}" required>
                        </div>
                        <div class="form-row">
                            <label for="hl-{{ $i }}-icon">Icon</label>
                            <select id="hl-{{ $i }}-icon" name="highlights[{{ $i }}][icon]">
                                @foreach($icons as $value => $label)
                                    <option value="{{ $value }}" {{ ($h['icon'] ?? '') === $value ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                @endforeach
            </div>
        </section>

        <button type="submit" class="btn-admin">Save changes</button>
    </form>

    @foreach($aboutSections as $section)
        <form method="POST" action="{{ route('admin.about-sections.destroy', $section) }}" id="delete-section-{{ $section->id }}" onsubmit="return confirm('Delete this section?');" hidden>
            @csrf
            @method('DELETE')
        </form>
    @endforeach
</div>

<script>
    (function () {
        let counter = 1000;

        document.querySelectorAll('[data-add-row]').forEach((button) => {
            button.addEventListener('click', () => {
                const list = document.getElementById(button.dataset.addRow);
                const template = document.getElementById(button.dataset.addRow + 'Template');
                list.insertAdjacentHTML('beforeend', template.innerHTML.replaceAll('__i__', String(counter++)));
                const field = list.lastElementChild.querySelector('input, select, textarea');
                if (field) field.focus();
            });
        });

        document.addEventListener('click', (event) => {
            const button = event.target.closest('[data-remove-row]');
            if (button) button.closest('.repeat-row').remove();
        });
    })();
</script>
@endsection
