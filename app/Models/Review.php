<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected $fillable = ['name', 'quote', 'rating', 'is_active', 'from_website', 'display_order'];

    protected $casts = [
        'is_active' => 'boolean',
        'from_website' => 'boolean',
        'rating' => 'integer',
    ];

    public function scopeVisible(Builder $query): Builder
    {
        return $query->where('is_active', true)->orderBy('display_order')->orderBy('id');
    }

    public function scopePending(Builder $query): Builder
    {
        return $query->where('from_website', true)->where('is_active', false);
    }

    public function isPending(): bool
    {
        return $this->from_website && ! $this->is_active;
    }

    public function initials(): string
    {
        return collect(preg_split('/\s+/', trim($this->name)))
            ->filter()
            ->take(2)
            ->map(fn ($word) => mb_strtoupper(mb_substr($word, 0, 1)))
            ->join('');
    }
}
