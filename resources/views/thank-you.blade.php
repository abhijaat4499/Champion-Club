<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Thank You — {{ config('gym.name') }}</title>
<meta name="robots" content="noindex">
<meta name="theme-color" content="#09090a">

@include('partials.fonts')

@vite(['resources/css/app.css'])
</head>
<body class="h-[100svh] overflow-hidden bg-ink-950">

<main class="relative isolate flex h-full flex-col items-center justify-center gap-5 px-4 py-6">
    @img('thankyou', 'absolute inset-0 -z-20 size-full object-cover opacity-60', true)
    <div class="absolute inset-0 -z-10 bg-[radial-gradient(circle_at_50%_40%,rgba(9,9,10,0.55),rgba(9,9,10,0.95)_70%)]"></div>

    <div class="w-full max-w-md border border-white/10 bg-ink-900/85 px-7 py-8 text-center shadow-2xl backdrop-blur-xl sm:px-10 sm:py-10">
        <a href="{{ url('/') }}" class="inline-flex items-center gap-3">
            <img src="{{ asset('images/logo-sm.webp') }}" alt="" width="40" height="40" class="size-10 rounded-full ring-1 ring-gold-500/60">
            <span class="font-display text-2xl font-extrabold tracking-wide text-white uppercase">Champion<span class="text-gold-500">Club</span></span>
        </a>

        <span class="mx-auto mt-7 flex size-20 items-center justify-center rounded-full bg-gold-500 text-4xl text-ink-950 shadow-[0_0_0_12px_rgba(212,165,55,0.14)] [@media(max-height:640px)]:size-16" aria-hidden="true">
            @icon('check', '')
        </span>

        <h1 class="mt-6 font-display text-6xl leading-none font-extrabold text-white">Thank you!</h1>
        <p class="mx-auto mt-4 max-w-xs leading-relaxed text-white/70">Your details have been submitted successfully. Our team will get back to you shortly.</p>
        @if(session('lead_subject'))
            <p class="mx-auto mt-4 inline-flex max-w-xs items-start gap-2 rounded-sm border border-gold-500/40 bg-gold-500/10 px-3 py-2 text-left text-sm font-semibold text-white">@icon('check', 'mt-0.5 shrink-0 text-gold-400') {{ session('lead_subject') }}</p>
        @endif
    </div>

    <div class="grid w-full max-w-[300px] gap-3">
        <a href="{{ url('/') }}" class="btn btn-gold w-full">Back to home</a>
        <a href="{{ $gymContact->whatsappUrl() }}" target="_blank" rel="noopener" class="btn btn-whatsapp w-full">@icon('whatsapp', '') Connect with us now</a>
    </div>
</main>

</body>
</html>
