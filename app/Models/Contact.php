<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    const INTERESTS = [
        'trial' => 'Free trial',
        'membership' => 'Membership plans',
        'offer' => 'Limited time offers',
        'coaching' => 'Personal coaching',
        'service' => 'Services',
        'other' => 'Something else',
    ];

    protected $fillable = ['name', 'email', 'phone', 'interest', 'subject', 'message'];

    public function interestLabel(): ?string
    {
        if (! $this->interest) {
            return null;
        }

        return self::INTERESTS[$this->interest] ?? ucfirst($this->interest);
    }

    public function callableNumber(): ?string
    {
        $digits = preg_replace('/\D/', '', (string) $this->phone);

        if (strlen($digits) === 11 && str_starts_with($digits, '0')) {
            $digits = substr($digits, 1);
        } elseif (strlen($digits) === 12 && str_starts_with($digits, '91')) {
            $digits = substr($digits, 2);
        }

        return preg_match('/^[6-9]\d{9}$/', $digits) ? '0' . $digits : null;
    }
}
