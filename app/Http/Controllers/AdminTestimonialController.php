<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\VideoTestimonial;
use App\Support\SiteSettings;
use Illuminate\Http\Request;

class AdminTestimonialController extends Controller
{
    public function index()
    {
        return view('admin.testimonials.index', [
            'pending' => Review::pending()->latest()->get(),
            'reviews' => Review::where(fn ($query) => $query->where('from_website', false)->orWhere('is_active', true))
                ->orderBy('display_order')->orderBy('id')->get(),
            'videos' => VideoTestimonial::orderBy('display_order')->orderBy('id')->get(),
            'youtube' => config('gym.social.youtube'),
        ]);
    }

    public function create()
    {
        return view('admin.testimonials.create');
    }

    public function store(Request $request)
    {
        Review::create($this->validated($request));

        return redirect()->route('admin.testimonials.index')->with('success', 'Review added.');
    }

    public function edit(Review $review)
    {
        return view('admin.testimonials.edit', compact('review'));
    }

    public function update(Request $request, Review $review)
    {
        $review->update($this->validated($request));

        return redirect()->route('admin.testimonials.index')->with('success', 'Review updated.');
    }

    public function approve(Review $review)
    {
        $review->update(['is_active' => true]);

        return redirect()->route('admin.testimonials.index')->with('success', 'Review approved. It is now live on the home page.');
    }

    public function destroy(Review $review)
    {
        $review->delete();

        return redirect()->route('admin.testimonials.index')->with('success', 'Review deleted.');
    }

    public function updateYoutube(Request $request)
    {
        $data = $request->validate([
            'youtube' => 'nullable|url|max:255',
        ], [
            'youtube.url' => 'Please enter a full link, e.g. https://www.youtube.com/@yourgym',
        ]);

        SiteSettings::save(['social' => array_merge((array) config('gym.social'), ['youtube' => $data['youtube'] ?? null])]);

        return redirect()->to(route('admin.testimonials.index') . '#videos')->with('success', 'YouTube channel link updated.');
    }

    private function validated(Request $request): array
    {
        $data = $request->validate([
            'name' => 'required|string|max:80',
            'quote' => 'required|string|max:600',
            'rating' => 'required|integer|between:1,5',
            'display_order' => 'required|integer|min:0',
        ]);

        $data['is_active'] = $request->boolean('is_active');

        return $data;
    }
}
