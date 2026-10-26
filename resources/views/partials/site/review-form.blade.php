@php
    $reviewErrors = $errors->getBag('review');
    $chosenRating = (int) old('review_rating');
@endphp

<div id="write-review" class="review-panel mt-14 grid scroll-mt-28 gap-10 border border-white/10 bg-ink-900/85 p-7 backdrop-blur sm:p-10 lg:grid-cols-12 lg:gap-14" data-reveal>
    <div class="lg:col-span-5">
        <p class="eyebrow">Your review</p>
        <h3 class="display-md mt-5 text-white">Trained with us? Tell us how it went.</h3>
        <p class="mt-4 text-white/60">Your words help the next person walk in with confidence. It takes less than a minute.</p>
        <p class="mt-6 flex items-start gap-3 text-sm text-white/50">
            @icon('check', 'mt-0.5 shrink-0 text-gold-500')
            <span>Reviews appear on the website after a quick check by our team.</span>
        </p>
    </div>

    <div class="lg:col-span-7">
        @if(session('review_sent'))
            <div class="flex h-full flex-col items-start justify-center gap-4 rounded-sm border border-gold-500/40 bg-gold-500/10 p-8" role="status">
                <span class="flex gap-1 text-xl text-gold-400" aria-hidden="true">
                    @for($s = 1; $s <= 5; $s++) @icon('star-solid', '') @endfor
                </span>
                <p class="font-display text-2xl font-semibold text-white">Thank you for your review!</p>
                <p class="text-white/65">We have received it. It will show on the website once our team has checked it.</p>
            </div>
        @else
            <form action="{{ route('gym.reviews.store') }}" method="POST" class="grid gap-5" novalidate>
                @csrf

                <div class="hidden" aria-hidden="true">
                    <label for="review-website">Leave this empty</label>
                    <input type="text" id="review-website" name="website" tabindex="-1" autocomplete="off">
                </div>

                <div>
                    <label for="review-name" class="field-label text-white/80">Your name</label>
                    <input type="text" id="review-name" name="review_name" value="{{ old('review_name') }}" autocomplete="name" maxlength="80" required
                           class="field field-dark" placeholder="Full name"
                           @if($reviewErrors->has('review_name')) aria-invalid="true" aria-describedby="review-name-error" @endif>
                    @if($reviewErrors->has('review_name'))
                        <span id="review-name-error" class="field-error">{{ $reviewErrors->first('review_name') }}</span>
                    @endif
                </div>

                <fieldset>
                    <legend class="field-label text-white/80">Your rating</legend>
                    <div class="star-input" @if($reviewErrors->has('review_rating')) aria-describedby="review-rating-error" @endif>
                        @for($s = 5; $s >= 1; $s--)
                            <input type="radio" id="review-star-{{ $s }}" name="review_rating" value="{{ $s }}" {{ $chosenRating === $s ? 'checked' : '' }} required>
                            <label for="review-star-{{ $s }}" title="{{ $s }} {{ $s === 1 ? 'star' : 'stars' }}">
                                @icon('star-solid', '')
                                <span class="sr-only">{{ $s }} {{ $s === 1 ? 'star' : 'stars' }}</span>
                            </label>
                        @endfor
                    </div>
                    @if($reviewErrors->has('review_rating'))
                        <span id="review-rating-error" class="field-error">{{ $reviewErrors->first('review_rating') }}</span>
                    @endif
                </fieldset>

                <div>
                    <label for="review-quote" class="field-label text-white/80">Your review</label>
                    <textarea id="review-quote" name="review_quote" rows="4" maxlength="600" required
                              class="field field-dark resize-y" placeholder="What do you like about training here?"
                              @if($reviewErrors->has('review_quote')) aria-invalid="true" aria-describedby="review-quote-error" @endif>{{ old('review_quote') }}</textarea>
                    @if($reviewErrors->has('review_quote'))
                        <span id="review-quote-error" class="field-error">{{ $reviewErrors->first('review_quote') }}</span>
                    @endif
                </div>

                <button type="submit" class="btn btn-gold mt-1 w-full sm:w-auto sm:justify-self-start">Submit review @icon('arrow', 'btn-arrow')</button>
            </form>
        @endif
    </div>
</div>
