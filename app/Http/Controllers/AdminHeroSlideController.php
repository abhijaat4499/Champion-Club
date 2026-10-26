<?php

namespace App\Http\Controllers;

use App\Models\HeroSlide;
use App\Support\ImageUpload;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminHeroSlideController extends Controller
{
    public function index()
    {
        $slides = HeroSlide::orderBy('display_order')->orderBy('id')->get();

        return view('admin.hero.index', compact('slides'));
    }

    public function create()
    {
        return view('admin.hero.create');
    }

    public function store(Request $request)
    {
        $data = $this->validated($request, true);
        $data += $this->storeImage($request);

        HeroSlide::create($data);

        return redirect()->route('admin.hero.index')->with('success', 'Slide added to the home page.');
    }

    public function edit(HeroSlide $slide)
    {
        return view('admin.hero.edit', compact('slide'));
    }

    public function update(Request $request, HeroSlide $slide)
    {
        $data = $this->validated($request, false);

        if ($request->hasFile('image')) {
            $this->deleteImages($slide);
            $data += $this->storeImage($request);
        }

        $slide->update($data);

        return redirect()->route('admin.hero.index')->with('success', 'Slide updated.');
    }

    public function destroy(HeroSlide $slide)
    {
        $this->deleteImages($slide);
        $slide->delete();

        return redirect()->route('admin.hero.index')->with('success', 'Slide deleted.');
    }

    private function storeImage(Request $request): array
    {
        [$desktop, $mobile] = ImageUpload::storeVariants($request->file('image'), 'hero', [[1920, 78], [900, 72]]);

        return [
            'image' => $desktop['path'],
            'image_mobile' => $mobile['path'],
            'site_image' => null,
        ];
    }

    private function deleteImages(HeroSlide $slide): void
    {
        foreach (array_filter([$slide->image, $slide->image_mobile]) as $path) {
            Storage::disk('public')->delete($path);
        }
    }

    private function validated(Request $request, bool $creating): array
    {
        $targets = implode(',', array_keys(HeroSlide::TARGETS));

        $data = $request->validate([
            'tab' => 'required|string|max:24',
            'label' => 'nullable|string|max:80',
            'title' => 'required|string|max:160',
            'highlight' => [
                'nullable', 'string', 'max:80',
                function ($attribute, $value, $fail) use ($request) {
                    if ($value && ! str_contains((string) $request->input('title'), $value)) {
                        $fail('The gold words must be copied exactly from the heading.');
                    }
                },
            ],
            'description' => 'nullable|string|max:400',
            'pattern' => 'required|in:' . implode(',', array_keys(HeroSlide::PATTERNS)),
            'points_style' => 'required|in:' . implode(',', array_keys(HeroSlide::POINT_STYLES)),
            'points' => 'nullable|string|max:1000',
            'image' => ($creating ? 'required|' : 'nullable|') . ImageUpload::RULE,
            'image_position' => 'required|in:' . implode(',', array_keys(HeroSlide::POSITIONS)),
            'button_text' => 'nullable|string|max:40',
            'button_target' => "nullable|required_with:button_text|in:{$targets}",
            'button2_text' => 'nullable|string|max:40',
            'button2_target' => "nullable|required_with:button2_text|in:{$targets}",
            'display_order' => 'required|integer|min:0',
        ], [
            'tab.required' => 'Please enter a short tab name, e.g. Strength.',
            'image.required' => 'Please choose a photo for this slide.',
            'button_target.required_with' => 'Choose where the first button should go.',
            'button2_target.required_with' => 'Choose where the second button should go.',
        ] + ImageUpload::messages('image'));

        unset($data['image']);
        $data['is_active'] = $request->boolean('is_active');

        return $data;
    }
}
