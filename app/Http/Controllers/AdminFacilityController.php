<?php

namespace App\Http\Controllers;

use App\Models\Facility;
use App\Models\Service;
use App\Support\ImageUpload;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminFacilityController extends Controller
{
    public function index()
    {
        $facilities = Facility::orderBy('display_order')->orderBy('id')->get();

        return view('admin.facilities.index', compact('facilities'));
    }

    public function create()
    {
        return view('admin.facilities.create');
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);

        if ($request->hasFile('image')) {
            $data['image'] = ImageUpload::store($request->file('image'), 'facilities', 1600)['path'];
        }

        Facility::create($data);

        return redirect()->route('admin.facilities.index')->with('success', 'Facility added.');
    }

    public function edit(Facility $facility)
    {
        return view('admin.facilities.edit', compact('facility'));
    }

    public function update(Request $request, Facility $facility)
    {
        $data = $this->validated($request);

        if ($request->hasFile('image')) {
            if ($facility->image) {
                Storage::disk('public')->delete($facility->image);
            }

            $data['image'] = ImageUpload::store($request->file('image'), 'facilities', 1600)['path'];
        } elseif ($request->boolean('remove_image') && $facility->image) {
            Storage::disk('public')->delete($facility->image);
            $data['image'] = null;
        }

        $facility->update($data);

        return redirect()->route('admin.facilities.index')->with('success', 'Facility updated.');
    }

    public function destroy(Facility $facility)
    {
        if ($facility->image) {
            Storage::disk('public')->delete($facility->image);
        }

        $facility->delete();

        return redirect()->route('admin.facilities.index')->with('success', 'Facility deleted.');
    }

    private function validated(Request $request): array
    {
        $data = $request->validate([
            'title' => 'required|string|max:80',
            'text' => 'required|string|max:300',
            'image' => ImageUpload::rule(),
            'image_position' => 'nullable|in:' . implode(',', array_keys(Service::IMAGE_POSITIONS)),
            'display_order' => 'required|integer|min:0',
        ], ImageUpload::messages('image'));

        unset($data['image']);
        $data['image_position'] = $data['image_position'] ?? 'center';
        $data['is_active'] = $request->boolean('is_active');

        return $data;
    }
}
