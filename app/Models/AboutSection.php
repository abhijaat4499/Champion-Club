<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class AboutSection extends Model
{
    protected $fillable = ['eyebrow', 'title', 'body', 'image', 'image_position', 'is_active', 'display_order'];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function scopeVisible(Builder $query): Builder
    {
        return $query->where('is_active', true)->orderBy('display_order')->orderBy('id');
    }

    public function imageUrl(): ?string
    {
        return $this->image ? asset('storage/' . $this->image) : null;
    }

    public function imageFocus(): string
    {
        return 'object-position: ' . (Service::IMAGE_POSITIONS[$this->image_position]['css'] ?? '50% 50%');
    }

    public function bodyBlocks(): array
    {
        return (new Service(['description' => $this->body]))->descriptionBlocks();
    }
}
