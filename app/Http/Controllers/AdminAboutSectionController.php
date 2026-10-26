<?php

namespace App\Http\Controllers;

use App\Models\AboutSection;
use App\Models\Service;
use App\Support\ImageUpload;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminAboutSectionController extends Controller
{
    public function index()
    {
        return redirect()->to(route('admin.site.edit') . '#about-gym');
    }

    public function create()
    {
        return view('admin.about-sections.create');
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);

        if ($request->hasFile('image')) {
            $data['image'] = ImageUpload::store($request->file('image'), 'about', 1400)['path'];
        }

        AboutSection::create($data);

        return redirect()->to(route('admin.site.edit') . '#about-gym')->with('success', 'Section added to the About page.');
    }

    public function edit(AboutSection $section)
    {
        return view('admin.about-sections.edit', compact('section'));
    }

    public function update(Request $request, AboutSection $section)
    {
        $data = $this->validated($request);

        if ($request->hasFile('image')) {
            if ($section->image) {
                Storage::disk('public')->delete($section->image);
            }

            $data['image'] = ImageUpload::store($request->file('image'), 'about', 1400)['path'];
        } elseif ($request->boolean('remove_image') && $section->image) {
            Storage::disk('public')->delete($section->image);
            $data['image'] = null;
        }

        $section->update($data);

        return redirect()->to(route('admin.site.edit') . '#about-gym')->with('success', 'Section updated.');
    }

    public function destroy(AboutSection $section)
    {
        if ($section->image) {
            Storage::disk('public')->delete($section->image);
        }

        $section->delete();

        return redirect()->to(route('admin.site.edit') . '#about-gym')->with('success', 'Section deleted.');
    }

    private function validated(Request $request): array
    {
        $data = $request->validate([
            'eyebrow' => 'nullable|string|max:60',
            'title' => 'required|string|max:150',
            'body' => 'required|string|max:8000',
            'image' => ImageUpload::rule(),
            'image_position' => 'nullable|in:' . implode(',', array_keys(Service::IMAGE_POSITIONS)),
            'display_order' => 'required|integer|min:0',
        ], [
            'title.required' => 'Please enter a heading.',
            'body.required' => 'Please write the section text.',
        ] + ImageUpload::messages('image'));

        unset($data['image']);
        $data['image_position'] = $data['image_position'] ?? 'center';
        $data['is_active'] = $request->boolean('is_active');

        return $data;
    }
}
