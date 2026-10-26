<?php

namespace Database\Seeders;

use App\Models\HeroSlide;
use Illuminate\Database\Seeder;

class HeroSlideSeeder extends Seeder
{
    public function run(): void
    {
        if (HeroSlide::exists()) {
            return;
        }

        foreach (HeroSlide::defaults() as $order => $slide) {
            HeroSlide::create($slide + ['is_active' => true, 'display_order' => $order + 1]);
        }
    }
}
