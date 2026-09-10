<?php

namespace Database\Seeders;

use App\Models\Trainer;
use Illuminate\Database\Seeder;

class TrainerSeeder extends Seeder
{
    public function run(): void
    {
        Trainer::create([
            'name' => 'Arjun Rathi',
            'role' => 'Strength & Powerlifting Coach',
            'bio' => 'Former state-level powerlifter with 9 years of coaching lifters from their first squat to their first meet.',
            'display_order' => 1,
        ]);

        Trainer::create([
            'name' => 'Meera Chandok',
            'role' => 'Boxing & Conditioning Coach',
            'bio' => 'Ex-national boxer who now builds conditioning programs that borrow directly from fight camp training.',
            'display_order' => 2,
        ]);

        Trainer::create([
            'name' => 'Vikram Sethi',
            'role' => 'Functional Training & Rehab Coach',
            'bio' => 'Background in sports rehabilitation, working with athletes recovering from injuries before they return to competition.',
            'display_order' => 3,
        ]);

        Trainer::create([
            'name' => 'Priya Nair',
            'role' => 'Yoga, Recovery & Nutrition Coach',
            'bio' => 'Combines a yoga teacher certification with a sports nutrition coaching background.',
            'display_order' => 4,
        ]);
    }
}