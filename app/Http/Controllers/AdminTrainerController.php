<?php

namespace App\Http\Controllers;

use App\Models\Trainer;
use App\Support\ImageUpload;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminTrainerController extends Controller
{
    public function index()
    {
        $trainers = Trainer::orderBy('display_order')->get();

        return view('admin.trainers.index', compact('trainers'));
    }

    public function create()
    {
        return view('admin.trainers.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'role' => 'required|string|max:255',
            'bio' => 'required|string',
            'photo' => ImageUpload::rule(),
            'display_order' => 'required|integer|min:0',
        ], ImageUpload::messages('photo'));

        if ($request->hasFile('photo')) {
            $photoPath = ImageUpload::store($request->file('photo'), 'trainers', 1000)['path'];

            $validated['photo'] = $photoPath;
        }

        Trainer::create($validated);

        return redirect()
            ->route('admin.trainers.index')
            ->with('success', 'Trainer created successfully.');
    }

    public function edit(Trainer $trainer)
    {
        return view('admin.trainers.edit', compact('trainer'));
    }

    public function update(Request $request, Trainer $trainer)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'role' => 'required|string|max:255',
            'bio' => 'required|string',
            'photo' => ImageUpload::rule(),
            'display_order' => 'required|integer|min:0',
        ], ImageUpload::messages('photo'));

        if ($request->hasFile('photo')) {
            if ($trainer->photo) {
                Storage::disk('public')->delete($trainer->photo);
            }

            $validated['photo'] = ImageUpload::store($request->file('photo'), 'trainers', 1000)['path'];
        } else {
            unset($validated['photo']);
        }

        $trainer->update($validated);

        return redirect()
            ->route('admin.trainers.index')
            ->with('success', 'Trainer updated successfully.');
    }

    public function destroy(Trainer $trainer)
    {
        if ($trainer->photo) {
            Storage::disk('public')->delete($trainer->photo);
        }

        $trainer->delete();

        return redirect()
            ->route('admin.trainers.index')
            ->with('success', 'Trainer deleted successfully.');
    }
}