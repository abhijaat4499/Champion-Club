<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Plan;

class PlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         Plan::create([
            'name' => 'Starter', 'price' => 1499, 'period' => '/month',
            'features' => "Full gym floor access\nLocker and shower access\nOne induction session with a coach",
            'is_featured' => false, 'color' => 'bronze', 'button_text' => 'Choose Starter', 'display_order' => 1,
        ]);

        Plan::create([
            'name' => 'Champion', 'price' => 2999, 'period' => '/month',
            'features' => "Everything in Starter\nUnlimited group classes\nMonthly progress check-in",
            'is_featured' => true, 'color' => 'gold', 'button_text' => 'Choose Champion', 'display_order' => 2,
        ]);

        Plan::create([
            'name' => 'Elite', 'price' => 4999, 'period' => '/month',
            'features' => "Everything in Champion\n4 personal coaching sessions\nCustom nutrition plan",
            'is_featured' => false, 'color' => 'silver', 'button_text' => 'Choose Elite', 'display_order' => 3,
        ]);
    }
}
