<?php

namespace Database\Seeders;

use App\Models\Facility;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class FacilitySeeder extends Seeder
{
    public function run(): void
    {
        if (Facility::exists()) {
            return;
        }

        $facilities = [
            ['title' => 'Lifting platforms & racks', 'image' => 'fac-platforms', 'text' => 'Competition-grade barbells and platforms, maintained on a weekly schedule.'],
            ['title' => 'Free weights', 'image' => 'fac-free-weights', 'text' => 'Full dumbbell runs and benches for accessory and hypertrophy work.'],
            ['title' => 'Functional zone', 'image' => 'fac-turf', 'text' => 'Kettlebells, sleds and sandbags for movement that carries over to real life.'],
            ['title' => 'Cardio zone', 'image' => 'fac-cardio', 'text' => 'Treadmills, bikes and rowers for conditioning and warm-ups.'],
            ['title' => 'Lockers & showers', 'image' => 'fac-lockers', 'text' => 'Locker and shower access is included with every membership plan.'],
        ];

        foreach ($facilities as $order => $data) {
            $image = null;
            $source = public_path(config("site-images.{$data['image']}.src", ''));

            if (is_file($source)) {
                $image = 'facilities/' . $data['image'] . '.webp';
                Storage::disk('public')->put($image, file_get_contents($source));
            }

            Facility::create([
                'title' => $data['title'],
                'text' => $data['text'],
                'image' => $image,
                'is_active' => true,
                'display_order' => $order + 1,
            ]);
        }
    }
}
