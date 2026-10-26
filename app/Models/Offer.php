<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class Offer extends Model
{
    const EVENTS = [
        'new_year' => 'New Year',
        'holi' => 'Holi',
        'diwali' => 'Diwali',
        'christmas' => 'Christmas',
        'other' => 'Others',
    ];

    const TIMEZONE = 'Asia/Kolkata';

    const NEW_YEAR_RANGE = 20;

    protected $fillable = ['title', 'event', 'new_year', 'price', 'original_price', 'starts_on', 'valid_until', 'description', 'is_active', 'display_order'];

    protected $casts = [
        'starts_on' => 'date',
        'valid_until' => 'date',
        'is_active' => 'boolean',
        'new_year' => 'integer',
    ];

    public static function today(): Carbon
    {
        return Carbon::now(self::TIMEZONE)->startOfDay();
    }

    public static function newYearRange(?int $keep = null): array
    {
        $min = self::today()->year;
        $max = $min + self::NEW_YEAR_RANGE;

        if ($keep && $keep < $min) {
            $min = $keep;
        }

        return [$min, $max];
    }

    public static function suggestedNewYear(): int
    {
        return self::today()->year + 1;
    }

    public function newYearNumber(): int
    {
        if ($this->new_year) {
            return $this->new_year;
        }

        return $this->valid_until->month === 12 ? $this->valid_until->year + 1 : $this->valid_until->year;
    }

    public function scopeVisible(Builder $query): Builder
    {
        return $query->where('is_active', true)
            ->where(fn (Builder $q) => $q->whereNull('starts_on')->orWhereDate('starts_on', '<=', self::today()->toDateString()))
            ->whereDate('valid_until', '>=', self::today()->toDateString())
            ->orderBy('display_order')
            ->orderBy('valid_until');
    }

    public function eventLabel(): string
    {
        return self::EVENTS[$this->event] ?? self::EVENTS['other'];
    }

    public function greeting(): string
    {
        return match ($this->event) {
            'new_year' => 'Happy New Year ' . $this->newYearNumber(),
            'holi' => 'Happy Holi',
            'diwali' => 'Happy Diwali',
            'christmas' => 'Merry Christmas',
            default => 'Special offer',
        };
    }

    public function isScheduled(): bool
    {
        return $this->starts_on && $this->starts_on->toDateString() > self::today()->toDateString();
    }

    public function isLive(): bool
    {
        return $this->is_active && ! $this->isScheduled() && ! $this->isExpired();
    }

    public function isExpired(): bool
    {
        return $this->valid_until->toDateString() < self::today()->toDateString();
    }

    public function daysLeft(): int
    {
        return (int) self::today()->diffInDays(Carbon::parse($this->valid_until->toDateString(), self::TIMEZONE), false);
    }

    public function discountPercent(): ?int
    {
        if (! $this->original_price || $this->original_price <= $this->price) {
            return null;
        }

        return (int) round(100 - ($this->price / $this->original_price * 100));
    }

    public function descriptionLines(): array
    {
        return array_values(array_filter(array_map('trim', explode("\n", $this->description))));
    }
}
