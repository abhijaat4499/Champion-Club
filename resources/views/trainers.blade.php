@extends('layouts.app')

@section('title', 'Our Coaches — Certified Personal Trainers | ChampionClub ' . config('gym.address.locality'))
@section('description', 'Meet the ChampionClub coaching team in ' . config('gym.address.locality') . ' — strength, functional training and personal coaches. Book a personal training session.')
@section('og_image', 'page-trainers')

@section('content')

<x-site.page-hero image="page-trainers" eyebrow="Coaches" title="Coaches who've done the work." crumb="Trainers">
    Every coach at ChampionClub has competed, not just certified. Here's who you'll actually be training with.
    <x-slot:actions>
        <a href="{{ route('gym.contact', ['interest' => 'coaching']) }}" class="btn btn-gold">Book a session @icon('arrow', 'btn-arrow')</a>
        <a href="{{ $gymContact->whatsappUrl('Hi, I want to book a personal training session') }}" target="_blank" rel="noopener" class="btn btn-ghost">@icon('whatsapp', '') WhatsApp us</a>
    </x-slot:actions>
</x-site.page-hero>

<section class="bg-ink-950 pt-10 pb-14 lg:pt-12 lg:pb-16">
    <div class="wrap">
        @if($trainers->isEmpty())
            <div class="mx-auto max-w-xl border border-white/10 bg-ink-900 p-10 text-center">
                <span class="icon-tile">@icon('users', '')</span>
                <h2 class="display-md mt-6 text-white">Trainers will be added soon.</h2>
                <a href="{{ route('gym.contact', ['interest' => 'coaching']) }}" class="btn btn-gold mt-8">Ask about coaching</a>
            </div>
        @else
            <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                @foreach($trainers as $trainer)
                    <div data-reveal style="--reveal-delay: {{ ($loop->index % 3) * 0.08 }}s">
                        @include('partials.site.trainer-card', ['trainer' => $trainer, 'bioLimit' => 600])
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</section>

<section class="bg-bone-100 pt-12 pb-14 text-ink-950 lg:pt-14 lg:pb-16">
    <div class="wrap">
        <div class="max-w-2xl" data-reveal>
            <p class="eyebrow !text-gold-700">Personal coaching</p>
            <h2 class="display-lg mt-5">Programming built entirely around your goal.</h2>
        </div>
        <ol class="mt-14 grid gap-px overflow-hidden rounded-sm border border-ink-950/10 bg-ink-950/10 md:grid-cols-3">
            @foreach([
                ['icon' => 'clipboard', 'title' => 'Custom program design', 'text' => 'One-on-one programming built around your specific goal.'],
                ['icon' => 'dumbbell', 'title' => 'Technique first', 'text' => 'Your coach corrects your form, not just your rep count.'],
                ['icon' => 'calendar', 'title' => 'Weekly check-ins', 'text' => 'Regular check-ins to adjust the plan as you progress.'],
            ] as $step)
                <li class="bg-bone-50 p-8 lg:p-10" data-reveal style="--reveal-delay: {{ $loop->index * 0.08 }}s">
                    <div class="flex items-center justify-between">
                        <span class="inline-flex size-12 items-center justify-center rounded-md bg-ink-950 text-xl text-gold-400">@icon($step['icon'], '')</span>
                        <span class="font-display text-5xl font-extrabold text-ink-950/10">{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>
                    </div>
                    <h3 class="mt-8 font-display text-[1.75rem] leading-tight">{{ $step['title'] }}</h3>
                    <p class="mt-2 leading-relaxed text-ink-950/65">{{ $step['text'] }}</p>
                </li>
            @endforeach
        </ol>
    </div>
</section>

@include('partials.site.cta-band', ['image' => 'gallery-7', 'title' => 'Train with someone who\'s been there.', 'text' => 'Book a free trial and get matched with the coach who fits your goal.'])

@endsection
