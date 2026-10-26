<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Trainer extends Model
{
    protected $fillable = ['name', 'role', 'bio', 'photo', 'display_order'];

    public function initials()
    {
        $words = preg_split('/\s+/', trim($this->name));

        $first = mb_strtoupper(mb_substr($words[0], 0, 1));
        $second = isset($words[1]) ? mb_strtoupper(mb_substr($words[1], 0, 1)) : '';

        return $first . $second;
    }
}