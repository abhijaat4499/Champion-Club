<?php

namespace App\Support;

class GymContact
{
    private static function digits(string $number): string
    {
        $digits = preg_replace('/\D/', '', $number);

        if (strlen($digits) === 11 && str_starts_with($digits, '0')) {
            $digits = substr($digits, 1);
        } elseif (strlen($digits) === 12 && str_starts_with($digits, config('gym.country_code'))) {
            $digits = substr($digits, 2);
        }

        return $digits;
    }

    public function callUrl(): string
    {
        return 'tel:0' . self::digits(config('gym.phone'));
    }

    public function displayPhone(): string
    {
        $d = self::digits(config('gym.phone'));

        return '+' . config('gym.country_code') . ' ' . substr($d, 0, 5) . ' ' . substr($d, 5);
    }

    public function schemaPhone(): string
    {
        return '+' . config('gym.country_code') . self::digits(config('gym.phone'));
    }

    public function whatsappUrl(?string $message = null): string
    {
        $number = config('gym.country_code') . self::digits(config('gym.whatsapp'));

        return 'https://wa.me/' . $number . '?text=' . rawurlencode($message ?? config('gym.whatsapp_message'));
    }

    public function email(): string
    {
        return config('gym.email');
    }

    public function addressLine(): string
    {
        $a = config('gym.address');

        return $a['street'] . ', ' . $a['locality'] . ', ' . $a['region'];
    }

    public function mapsUrl(): string
    {
        $link = trim((string) config('gym.address.maps_url'));

        if ($link !== '') {
            return $link;
        }

        return 'https://www.google.com/maps/search/?api=1&query=' . rawurlencode(config('gym.name') . ', ' . $this->addressLine());
    }

    public function mapEmbedUrl(): string
    {
        $point = self::coordinates((string) config('gym.address.maps_url'));
        $query = $point ? $point[0] . ',' . $point[1] : $this->addressLine();

        return 'https://www.google.com/maps?q=' . rawurlencode($query) . '&output=embed';
    }

    public static function coordinates(string $link): ?array
    {
        $patterns = [
            '/!3d(-?\d{1,2}\.\d+)!4d(-?\d{1,3}\.\d+)/',
            '/@(-?\d{1,2}\.\d+),(-?\d{1,3}\.\d+)/',
            '/[?&](?:q|query|ll|destination)=(-?\d{1,2}\.\d+)(?:,|%2C)\s*(-?\d{1,3}\.\d+)/i',
        ];

        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $link, $match)) {
                return [$match[1], $match[2]];
            }
        }

        return null;
    }

    public function hours(): array
    {
        return config('gym.hours');
    }
}
