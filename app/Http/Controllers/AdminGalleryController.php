<?php

namespace App\Http\Controllers;

use App\Models\GalleryPhoto;
use App\Support\ImageUpload;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AdminGalleryController extends Controller
{
    public function index()
    {
        $photos = GalleryPhoto::orderBy('display_order')->orderBy('id')->get();

        return view('admin.gallery.index', compact('photos'));
    }

    public function create()
    {
        return view('admin.gallery.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'photos' => 'required|array|min:1|max:20',
            'photos.*' => ImageUpload::RULE,
            'alt' => 'nullable|string|max:160',
            'caption' => 'nullable|string|max:160',
        ], [
            'photos.required' => 'Please choose at least one photo.',
            'photos.max' => 'You can upload up to 20 photos at a time.',
            'photos.*.image' => 'Each file must be a JPG, PNG or WebP image.',
            'photos.*.mimes' => 'Each file must be a JPG, PNG or WebP image.',
            'photos.*.max' => 'Each photo must be 25 MB or smaller.',
        ]);

        $order = (int) GalleryPhoto::max('display_order');

        foreach ($request->file('photos') as $file) {
            $stored = ImageUpload::store($file, 'gallery');

            GalleryPhoto::create([
                'image' => $stored['path'],
                'width' => $stored['width'],
                'height' => $stored['height'],
                'alt' => $data['alt'] ?: Str::limit(Str::headline(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)), 160, ''),
                'caption' => $data['caption'] ?? null,
                'is_active' => $request->boolean('is_active'),
                'display_order' => ++$order,
            ]);
        }

        $count = count($request->file('photos'));

        return redirect()->route('admin.gallery.index')->with('success', $count === 1 ? 'Photo uploaded.' : "{$count} photos uploaded.");
    }

    public function edit(GalleryPhoto $photo)
    {
        return view('admin.gallery.edit', compact('photo'));
    }

    public function update(Request $request, GalleryPhoto $photo)
    {
        $data = $request->validate([
            'image' => ImageUpload::rule(),
            'alt' => 'required|string|max:160',
            'caption' => 'nullable|string|max:160',
        ]);

        if ($request->hasFile('image')) {
            $stored = ImageUpload::store($request->file('image'), 'gallery');
            Storage::disk('public')->delete($photo->image);
            $photo->fill(['image' => $stored['path'], 'width' => $stored['width'], 'height' => $stored['height']]);
        }

        $photo->fill([
            'alt' => $data['alt'],
            'caption' => $data['caption'] ?? null,
            'is_active' => $request->boolean('is_active'),
        ])->save();

        return redirect()->route('admin.gallery.index')->with('success', 'Photo updated.');
    }

    public function destroy(GalleryPhoto $photo)
    {
        Storage::disk('public')->delete($photo->image);
        $photo->delete();

        return redirect()->route('admin.gallery.index')->with('success', 'Photo deleted.');
    }

    public function bulkDestroy(Request $request)
    {
        $ids = $request->validate(['ids' => 'required|array|min:1', 'ids.*' => 'integer'])['ids'];
        $photos = GalleryPhoto::whereIn('id', $ids)->get();

        foreach ($photos as $photo) {
            Storage::disk('public')->delete($photo->image);
            $photo->delete();
        }

        return redirect()->route('admin.gallery.index')->with('success', $photos->count() . ' photo(s) deleted.');
    }

    public function reorder(Request $request)
    {
        $ids = $request->validate(['ids' => 'required|array', 'ids.*' => 'integer'])['ids'];

        foreach (array_values($ids) as $index => $id) {
            GalleryPhoto::whereKey($id)->update(['display_order' => $index + 1]);
        }

        return response()->json(['ok' => true]);
    }
}
