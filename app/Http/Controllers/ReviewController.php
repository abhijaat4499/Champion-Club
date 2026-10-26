<?php

namespace App\Http\Controllers;

use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Validator;

class ReviewController extends Controller
{
    public function store(Request $request)
    {
        $back = url('/') . '#write-review';

        // Bots fill the hidden field; pretend it worked and save nothing.
        if ($request->filled('website')) {
            return redirect()->to($back)->with('review_sent', true);
        }

        $key = 'review:' . $request->ip();

        if (RateLimiter::tooManyAttempts($key, 3)) {
            return redirect()->to($back)
                ->withErrors(['review_quote' => 'Thanks! We already have your review. Please try again later.'], 'review')
                ->withInput();
        }

        $validator = Validator::make($request->all(), [
            'review_name' => 'required|string|min:2|max:80',
            'review_rating' => 'required|integer|between:1,5',
            'review_quote' => 'required|string|min:10|max:600',
        ], [
            'review_name.required' => 'Please enter your name.',
            'review_name.min' => 'Please enter your name.',
            'review_rating.required' => 'Please choose a star rating.',
            'review_rating.*' => 'Please choose a star rating.',
            'review_quote.required' => 'Please write a few words about your experience.',
            'review_quote.min' => 'Please write at least 10 characters.',
            'review_quote.max' => 'Please keep your review under 600 characters.',
        ]);

        if ($validator->fails()) {
            return redirect()->to($back)->withErrors($validator, 'review')->withInput();
        }

        $data = $validator->validated();

        RateLimiter::hit($key, 3600);

        Review::create([
            'name' => trim($data['review_name']),
            'rating' => $data['review_rating'],
            'quote' => trim($data['review_quote']),
            'is_active' => false,
            'from_website' => true,
            'display_order' => (int) Review::max('display_order') + 1,
        ]);

        return redirect()->to($back)->with('review_sent', true);
    }
}
