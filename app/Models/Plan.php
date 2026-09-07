<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    protected $fillable = ['name', 'price', 'period', 'features', 'is_featured', 'color', 'button_text', 'display_order'];

    protected $casts = [
        'is_featured' => 'boolean',
    ];

    public function featureList()
    {
        return array_filter(array_map('trim', explode("\n", $this->features)));
    }
}