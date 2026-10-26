<?php

namespace App\Http\Controllers;

use App\Models\VideoTestimonial;
use Illuminate\Http\Request;

class AdminVideoTestimonialController extends Controller
{
    public function index()
    {
        return redirect()->to(route('admin.testimonials.index') . '#videos');
    }

    public function create()
    {
        return view('admin.videos.create');
    }

    public function store(Request $request)
    {
        VideoTestimonial::create($this->validated($request));

        return redirect()->to(route('admin.testimonials.index') . '#videos')->with('success', 'Video added.');
    }

    public function edit(VideoTestimonial $video)
    {
        return view('admin.videos.edit', compact('video'));
    }

    public function update(Request $request, VideoTestimonial $video)
    {
        $video->update($this->validated($request));

        return redirect()->to(route('admin.testimonials.index') . '#videos')->with('success', 'Video updated.');
    }

    public function destroy(VideoTestimonial $video)
    {
        $video->delete();

        return redirect()->to(route('admin.testimonials.index') . '#videos')->with('success', 'Video deleted.');
    }

    private function validated(Request $request): array
    {
        $request->merge(['youtube_id' => VideoTestimonial::parseYoutubeId($request->input('youtube_url'))]);

        $data = $request->validate([
            'youtube_url' => 'required|string|max:255',
            'youtube_id' => 'required|string|size:11',
            'title' => 'required|string|max:120',
            'name' => 'nullable|string|max:80',
            'display_order' => 'required|integer|min:0',
        ], [
            'youtube_id.required' => 'Please paste a valid YouTube video link, e.g. https://www.youtube.com/watch?v=XXXXXXXXXXX',
            'youtube_id.size' => 'Please paste a valid YouTube video link.',
        ]);

        unset($data['youtube_url']);
        $data['is_active'] = $request->boolean('is_active');

        return $data;
    }
}
