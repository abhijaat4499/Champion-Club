<?php

namespace App\Support;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use RuntimeException;

class ImageUpload
{
    const MAX_KB = 25600;

    const RULE = 'image|mimes:jpg,jpeg,png,webp|max:' . self::MAX_KB;

    const HINT = 'Any JPG, PNG or WebP photo up to 25 MB — large photos are resized and compressed automatically.';

    public static function rule(): string
    {
        return 'nullable|' . self::RULE;
    }

    public static function messages(string $field): array
    {
        return [
            "{$field}.image" => 'Please choose a JPG, PNG or WebP photo.',
            "{$field}.mimes" => 'Please choose a JPG, PNG or WebP photo.',
            "{$field}.max" => 'This photo is larger than 25 MB. Please choose a smaller one.',
            "{$field}.uploaded" => 'This photo could not be uploaded. Please choose a smaller one.',
        ];
    }

    public static function store(UploadedFile $file, string $dir, int $max = 1400, int $quality = 80): array
    {
        return self::storeFromPath($file->getRealPath(), $dir, $max, $quality, $file->getMimeType());
    }

    public static function storeFromPath(string $path, string $dir, int $max = 1400, int $quality = 80, ?string $mime = null): array
    {
        return self::variantsFromPath($path, $dir, [[$max, $quality]], $mime)[0];
    }

    public static function storeVariants(UploadedFile $file, string $dir, array $sizes): array
    {
        return self::variantsFromPath($file->getRealPath(), $dir, $sizes, $file->getMimeType());
    }

    private static function variantsFromPath(string $path, string $dir, array $sizes, ?string $mime): array
    {
        @ini_set('memory_limit', '1024M');

        $source = @imagecreatefromstring((string) file_get_contents($path));

        if (! $source) {
            throw new RuntimeException('Unsupported image file.');
        }

        $source = self::orient($source, $path, $mime);
        $results = [];

        usort($sizes, fn ($a, $b) => $b[0] <=> $a[0]);

        foreach ($sizes as [$max, $quality]) {
            $width = imagesx($source);
            $height = imagesy($source);
            $scale = min(1, $max / max($width, $height));
            $newWidth = max(1, (int) round($width * $scale));
            $newHeight = max(1, (int) round($height * $scale));

            $canvas = imagecreatetruecolor($newWidth, $newHeight);
            imagealphablending($canvas, false);
            imagesavealpha($canvas, true);
            imagecopyresampled($canvas, $source, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);

            ob_start();
            imagewebp($canvas, null, $quality);
            $data = ob_get_clean();

            $stored = trim($dir, '/') . '/' . Str::random(32) . '.webp';
            Storage::disk('public')->put($stored, $data);
            $results[] = ['path' => $stored, 'width' => $newWidth, 'height' => $newHeight];

            imagedestroy($source);
            $source = $canvas;
        }

        imagedestroy($source);

        return $results;
    }

    private static function orient(\GdImage $image, string $path, ?string $mime): \GdImage
    {
        if ($mime !== 'image/jpeg' || ! function_exists('exif_read_data')) {
            return $image;
        }

        $orientation = (int) (@exif_read_data($path)['Orientation'] ?? 1);
        $angle = [3 => 180, 6 => -90, 8 => 90][$orientation] ?? 0;

        if (! $angle) {
            return $image;
        }

        $rotated = imagerotate($image, $angle, 0);
        imagedestroy($image);

        return $rotated;
    }
}
