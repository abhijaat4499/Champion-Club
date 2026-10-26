<?php

namespace Database\Seeders;

use App\Models\Review;
use Illuminate\Database\Seeder;

class ReviewSeeder extends Seeder
{
    public function run(): void
    {
        if (Review::exists()) {
            return;
        }

        $reviews = [
            [
                'quote' => "I've trained at four gyms in this city. ChampionClub is the first place where coaches actually watch your form instead of scrolling their phone between sets.",
                'name' => 'Ritika Sinha',
                'rating' => 5,
            ],
            [
                'quote' => "The 6 AM strength class changed how I think about consistency. Three years in, still haven't missed a Monday.",
                'name' => 'Karan Mehta',
                'rating' => 5,
            ],
            [
                'quote' => "I came in with a stiff back from a desk job. My coach rebuilt my deadlift from scratch and the pain is gone. I actually look forward to leg day now.",
                'name' => 'Arjun Malhotra',
                'rating' => 5,
            ],
            [
                'quote' => "Clean floor, clean washrooms and nobody hogging the racks. As a woman training alone in the evenings, I've always felt comfortable here.",
                'name' => 'Sneha Kapoor',
                'rating' => 5,
            ],
            [
                'quote' => "Lost 11 kg in five months without crash dieting. The weekly check-ins kept me honest and the plan changed whenever I hit a plateau.",
                'name' => 'Rohit Verma',
                'rating' => 5,
            ],
            [
                'quote' => "The cardio zone is never crowded and the coaches give you a proper warm-up plan. Good value for the price compared to the big chains.",
                'name' => 'Ananya Gupta',
                'rating' => 4,
            ],
            [
                'quote' => "I'm 52 and started lifting here for the first time. They took it slow, taught me technique first, and I've never felt stronger.",
                'name' => 'Vivek Choudhary',
                'rating' => 5,
            ],
        ];

        foreach ($reviews as $order => $review) {
            Review::create($review + ['is_active' => true, 'display_order' => $order + 1]);
        }
    }
}
