<?php

namespace App\Support;

use Illuminate\Support\Carbon;

class AdminOtp
{
    const VALID_MINUTES = 10;

    const RESEND_AFTER_SECONDS = 30;

    // A fresh code that never repeats the one it replaces.
    public static function generate(?string $previous = null): string
    {
        do {
            $code = (string) random_int(100000, 999999);
        } while ($code === (string) $previous);

        return $code;
    }

    public static function expiresAt(): Carbon
    {
        return now()->addMinutes(self::VALID_MINUTES);
    }

    // Seconds left before another code may be sent, based on when the current code was issued.
    public static function waitSeconds(?Carbon $expiresAt): int
    {
        if (! $expiresAt) {
            return 0;
        }

        $sentAt = $expiresAt->copy()->subMinutes(self::VALID_MINUTES);

        return max(0, (int) ceil(now()->diffInSeconds($sentAt->addSeconds(self::RESEND_AFTER_SECONDS), false)));
    }
}
