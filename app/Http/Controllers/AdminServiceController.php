<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Support\ImageUpload;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class AdminServiceController extends Controller
{
    public function index()
    {
        $services = Service::orderBy('display_order')->orderBy('title')->get();

        return view('admin.services.index', compact('services'));
    }

    public function create()
    {
        return view('admin.services.create', ['icons' => Service::ICONS]);
    }

    public function store(Request $request)
    {
        $validated = $this->validated($request);

        if ($request->hasFile('image')) {
            $validated['image'] = ImageUpload::store($request->file('image'), 'services', 1600)['path'];
        }

        Service::create($validated);

        return redirect()->route('admin.services.index')->with('success', 'Service created.');
    }

    public function edit(Service $service)
    {
        return view('admin.services.edit', ['service' => $service, 'icons' => Service::ICONS]);
    }

    public function update(Request $request, Service $service)
    {
        $validated = $this->validated($request, $service);

        if ($request->hasFile('image')) {
            if ($service->image) {
                Storage::disk('public')->delete($service->image);
            }

            $validated['image'] = ImageUpload::store($request->file('image'), 'services', 1600)['path'];
        } elseif ($request->boolean('remove_image') && $service->image) {
            Storage::disk('public')->delete($service->image);
            $validated['image'] = null;
        }

        $service->update($validated);

        return redirect()->route('admin.services.index')->with('success', 'Service updated.');
    }

    public function destroy(Service $service)
    {
        if ($service->image) {
            Storage::disk('public')->delete($service->image);
        }

        $service->delete();

        return redirect()->route('admin.services.index')->with('success', 'Service deleted.');
    }

    private function validated(Request $request, ?Service $service = null): array
    {
        $request->merge(['slug' => Str::slug($request->input('slug') ?: $request->input('title'))]);

        $validated = $request->validate([
            'title' => 'required|string|max:120',
            'slug' => ['required', 'string', 'max:140', 'alpha_dash', Rule::unique('services', 'slug')->ignore($service?->id)],
            'icon' => 'required|in:' . implode(',', array_keys(Service::ICONS)),
            'summary' => 'required|string|max:255',
            'description' => 'required|string|max:5000',
            'highlights' => 'nullable|string|max:2000',
            'image' => ImageUpload::rule(),
            'image_position' => 'nullable|in:' . implode(',', array_keys(Service::IMAGE_POSITIONS)),
            'display_order' => 'required|integer|min:0',
        ], [
            'slug.unique' => 'Another service already uses this page URL. Please change the title or URL.',
            'slug.required' => 'Please enter a title.',
        ] + ImageUpload::messages('image'));

        unset($validated['image']);
        $validated['image_position'] = $validated['image_position'] ?? 'center';
        $validated['is_active'] = $request->boolean('is_active');

        return $validated;
    }
}
