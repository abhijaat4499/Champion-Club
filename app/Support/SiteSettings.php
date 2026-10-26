<?php

namespace App\Support;

use App\Models\Setting;
use Illuminate\Support\Carbon;

class SiteSettings
{
    const KEYS = [
        'phone', 'whatsapp', 'email', 'whatsapp_message',
        'address', 'hours', 'social',
        'years_running', 'highlights', 'about_title', 'about_story', 'about_images',
    ];

    const DAYS = [
        'Mo' => ['short' => 'Mon', 'long' => 'Monday'],
        'Tu' => ['short' => 'Tue', 'long' => 'Tuesday'],
        'We' => ['short' => 'Wed', 'long' => 'Wednesday'],
        'Th' => ['short' => 'Thu', 'long' => 'Thursday'],
        'Fr' => ['short' => 'Fri', 'long' => 'Friday'],
        'Sa' => ['short' => 'Sat', 'long' => 'Saturday'],
        'Su' => ['short' => 'Sun', 'long' => 'Sunday'],
    ];

    public static function apply(?array $values = null): void
    {
        $values ??= self::stored();

        foreach (array_intersect_key($values, array_flip(self::KEYS)) as $key => $value) {
            config(["gym.{$key}" => $value]);
        }
    }

    public static function save(array $values): void
    {
        $values = array_intersect_key($values, array_flip(self::KEYS));

        foreach ($values as $key => $value) {
            Setting::updateOrCreate(['key' => $key], ['value' => $value]);
        }

        self::apply($values);
    }

    public static function stored(): array
    {
        try {
            return Setting::query()->get(['key', 'value'])->pluck('value', 'key')->all();
        } catch (\Throwable) {
            return [];
        }
    }

    public static function hoursRow(string $from, string $to, string $opens, string $closes): array
    {
        $days = $from === $to
            ? self::DAYS[$from]['long']
            : self::DAYS[$from]['short'] . ' – ' . self::DAYS[$to]['short'];

        $time = Carbon::createFromFormat('H:i', $opens)->format('g:i A') . ' – ' . Carbon::createFromFormat('H:i', $closes)->format('g:i A');

        return [
            'days' => $days,
            'time' => $time,
            'schema' => ($from === $to ? $from : "{$from}-{$to}") . " {$opens}-{$closes}",
            'from' => $from,
            'to' => $to,
            'opens' => $opens,
            'closes' => $closes,
        ];
    }

    public static function defaultStory(): string
    {
        $years = config('gym.years_running');

        return implode("\n\n", [
            "For {$years}+ years, ChampionClub has been built around one simple idea: people get stronger faster when someone is actually coaching them.",
            "That means certified trainers on the floor who correct your form, not just count your reps. It means competition-grade barbells and platforms, maintained on a weekly schedule. And it means a community of people who show up because they want to.",
            "Whether it's your first squat or your next meet, every program here is coached — from strength and conditioning to functional training, HIIT and cardio.",
        ]);
    }

    const ABOUT_IMAGE_DEFAULTS = [
        'main' => 'about-detail',
    ];

    public static function aboutImage(string $slot): ?array
    {
        $value = config("gym.about_images.{$slot}");

        if ($value === 'none') {
            return null;
        }

        if ($value) {
            return ['url' => asset('storage/' . $value)];
        }

        return ['site' => self::ABOUT_IMAGE_DEFAULTS[$slot]];
    }

    public static function storyParagraphs(): array
    {
        $story = config('gym.about_story') ?: self::defaultStory();

        return array_values(array_filter(array_map('trim', preg_split('/\R{2,}/', $story))));
    }
}
