<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    const ICONS = [
        'dumbbell' => 'Dumbbell',
        'muscle' => 'Strength',
        'boxing' => 'Boxing',
        'kettlebell' => 'Kettlebell',
        'stopwatch' => 'Stopwatch',
        'running' => 'Running',
        'yoga' => 'Yoga',
        'pulse' => 'Heart rate',
        'fire' => 'Fire',
        'bolt' => 'Energy',
        'trophy' => 'Trophy',
        'medal' => 'Medal',
        'users' => 'Group',
        'clipboard' => 'Plan',
        'shower' => 'Recovery',
        'area' => 'Space',
    ];

    const FALLBACK_IMAGE = 'page-programs';

    const IMAGE_POSITIONS = [
        'top' => ['label' => 'Top', 'css' => '50% 0%'],
        'upper' => ['label' => 'Upper', 'css' => '50% 25%'],
        'center' => ['label' => 'Center', 'css' => '50% 50%'],
        'lower' => ['label' => 'Lower', 'css' => '50% 75%'],
        'bottom' => ['label' => 'Bottom', 'css' => '50% 100%'],
    ];

    protected $fillable = ['title', 'slug', 'icon', 'summary', 'description', 'highlights', 'image', 'image_position', 'is_active', 'display_order'];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function scopeVisible(Builder $query): Builder
    {
        return $query->where('is_active', true)->orderBy('display_order')->orderBy('title');
    }

    public function highlightList(): array
    {
        return array_values(array_filter(array_map('trim', explode("\n", (string) $this->highlights))));
    }

    public function descriptionParagraphs(): array
    {
        return array_values(array_filter(array_map('trim', preg_split('/\R{2,}/', (string) $this->description))));
    }

    public function descriptionBlocks(): array
    {
        $lines = array_values(array_filter(array_map('trim', preg_split('/\R/', (string) $this->description))));

        $blocks = [];

        foreach ($lines as $line) {
            $heading = mb_strlen($line) <= 60 && ! preg_match('/[.,;:"”)]$/u', $line) && ! preg_match('/[.!?]\s/u', $line);
            $last = end($blocks);

            if (! $heading && $last && $last['type'] === 'text' && ! preg_match('/[.:;!?"”)]$/u', $last['text'])) {
                $blocks[key($blocks)]['text'] .= ' ' . $line;
                continue;
            }

            $blocks[] = ['type' => $heading ? 'heading' : 'text', 'text' => $line];
        }

        return $blocks;
    }

    public function iconName(): string
    {
        return array_key_exists($this->icon, self::ICONS) ? $this->icon : 'dumbbell';
    }

    public function imageFocus(int $shift = 0): string
    {
        $keys = array_keys(self::IMAGE_POSITIONS);
        $index = array_search($this->image_position, $keys, true);
        $index = max(0, min(count($keys) - 1, ($index === false ? 2 : $index) - $shift));

        return 'object-position: ' . self::IMAGE_POSITIONS[$keys[$index]]['css'];
    }

    public function heroFocus(): string
    {
        return $this->imageFocus($this->image_position === 'center' ? 0 : 1);
    }

    public function imageUrl(): ?string
    {
        return $this->image ? asset('storage/' . $this->image) : null;
    }
}
