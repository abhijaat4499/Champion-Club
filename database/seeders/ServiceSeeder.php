<?php

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class ServiceSeeder extends Seeder
{
    public function run(): void
    {
        $services = [
            [
                'slug' => 'strength-conditioning',
                'title' => 'Strength & Conditioning',
                'icon' => 'dumbbell',
                'image' => 'prog-strength',
                'summary' => 'Barbell fundamentals and progressive overload for real, measurable strength.',
                'description' => 'Barbell fundamentals and progressive overload for real, measurable strength — built around the squat, bench, and deadlift.',
                'highlights' => ['Beginner to advanced tracks', 'Technique work every session', 'Progress tracked every 4 weeks'],
            ],
            [
                'slug' => 'functional-training',
                'title' => 'Functional Training',
                'icon' => 'kettlebell',
                'image' => 'prog-functional',
                'summary' => 'Movement patterns that carry over to sport, work, and everyday life.',
                'description' => 'Movement patterns that carry over to sport, work, and everyday life — kettlebells, sleds, and sandbags over machines.',
                'highlights' => ['Focus on mobility and control', 'Great for injury prevention', 'Scales to any fitness level'],
            ],
            [
                'slug' => 'group-hiit',
                'title' => 'Group HIIT',
                'icon' => 'stopwatch',
                'image' => 'prog-hiit',
                'summary' => 'High-intensity interval sessions where the clock is the only opponent.',
                'description' => 'High-intensity interval sessions where the clock is the only opponent — a new format every week so it never gets stale.',
                'highlights' => ['45-minute sessions', 'New format every week', 'Runs three times daily on weekdays'],
            ],
            [
                'slug' => 'personal-coaching',
                'title' => 'Personal Coaching',
                'icon' => 'clipboard',
                'image' => 'prog-coaching',
                'summary' => 'One-on-one programming built entirely around your specific goal.',
                'description' => 'One-on-one programming built entirely around your specific goal, with weekly check-ins to adjust as you progress.',
                'highlights' => ['Custom program design', 'Weekly check-ins', 'Included with Champion and Elite plans'],
            ],
        ];

        foreach ($services as $order => $data) {
            if (Service::where('slug', $data['slug'])->exists()) {
                continue;
            }

            $image = null;
            $source = public_path(config("site-images.{$data['image']}.src", ''));

            if (is_file($source)) {
                $image = "services/{$data['slug']}.webp";
                Storage::disk('public')->put($image, file_get_contents($source));
            }

            Service::create([
                'slug' => $data['slug'],
                'title' => $data['title'],
                'icon' => $data['icon'],
                'summary' => $data['summary'],
                'description' => $data['description'],
                'highlights' => implode("\n", $data['highlights']),
                'image' => $image,
                'is_active' => true,
                'display_order' => $order + 1,
            ]);
        }
    }
}
