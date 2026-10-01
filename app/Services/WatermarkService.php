<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class WatermarkService
{
    /**
     * System TTF Font paths to check.
     */
    protected static array $ttfFonts = [
        'C:\Windows\Fonts\arial.ttf',
        'C:\Windows\Fonts\segoeui.ttf',
        'C:\Windows\Fonts\tahoma.ttf',
        '/usr/share/fonts/truetype/dejavu/DejaVuSans-Bold.ttf',
        '/usr/share/fonts/truetype/liberation/LiberationSans-Bold.ttf',
    ];

    /**
     * Apply repeated diagonal semi-transparent watermark to a clean contest image.
     *
     * @param string $sourceFullPath Absolute path to the clean uploaded image
     * @param int|string $entryId Contest Entry ID or Identifier
     * @param string|null $customText Custom watermark string
     * @return string Relative public storage path for watermarked image
     */
    public static function applyContestWatermark(string $sourceFullPath, int|string $entryId, ?string $customText = null): string
    {
        if (!file_exists($sourceFullPath)) {
            throw new \RuntimeException("Source image not found: {$sourceFullPath}");
        }

        $imageInfo = @getimagesize($sourceFullPath);
        if (!$imageInfo) {
            throw new \RuntimeException("Invalid image file format.");
        }

        $mime = $imageInfo['mime'];
        $width = $imageInfo[0];
        $height = $imageInfo[1];

        // Load image resource
        $image = match ($mime) {
            'image/jpeg', 'image/jpg' => @imagecreatefromjpeg($sourceFullPath),
            'image/png' => @imagecreatefrompng($sourceFullPath),
            'image/webp' => @imagecreatefromwebp($sourceFullPath),
            default => null,
        };

        if (!$image) {
            throw new \RuntimeException("Unsupported image type for watermarking: {$mime}");
        }

        // Enable alpha blending
        imagealphablending($image, true);
        imagesavealpha($image, true);

        $watermarkText = $customText ?: "NOKSHA CONTEST ENTRY #{$entryId} - FOR PREVIEW ONLY";

        // Locate usable TTF font
        $fontPath = null;
        foreach (self::$ttfFonts as $font) {
            if (file_exists($font)) {
                $fontPath = $font;
                break;
            }
        }

        if ($fontPath && function_exists('imagettftext')) {
            self::applyTtfWatermark($image, $width, $height, $watermarkText, $fontPath);
        } else {
            self::applyBasicWatermark($image, $width, $height, $watermarkText);
        }

        // Ensure target directory exists in public disk
        Storage::disk('public')->makeDirectory('contest_watermarked');

        $filename = 'entry_' . $entryId . '_' . Str::random(12) . '.jpg';
        $destinationFullPath = Storage::disk('public')->path('contest_watermarked/' . $filename);

        // Save as progressive high-quality JPEG
        imagejpeg($image, $destinationFullPath, 90);
        imagedestroy($image);

        return 'contest_watermarked/' . $filename;
    }

    /**
     * Stamping repeated diagonal semi-transparent TTF watermark across the entire canvas.
     * Subtle, elegant, ultra-transparent (10%-12% opacity) at 45-degree angle with generous spacing.
     */
    protected static function applyTtfWatermark($image, int $width, int $height, string $text, string $fontPath): void
    {
        $fontSize = max(11, (int) round($width / 52));
        $angle = 45; // Clean 45-degree diagonal angle

        // Semi-transparent colors (GD Alpha range: 0=opaque, 127=transparent)
        // 114 / 127 = ~90% transparent -> 10% opacity
        $shadowColor = imagecolorallocatealpha($image, 15, 23, 42, 118); // Ultra-faint slate shadow (7% opacity)
        $textColor = imagecolorallocatealpha($image, 255, 255, 255, 114); // Ultra-faint white text (10% opacity)

        // Generous line spacing & reduced pattern density
        $stepX = max(420, (int) round($width / 2.2));
        $stepY = max(320, (int) round($height / 2.4));

        $cleanWatermark = "NOKSHA PREVIEW • CONTEST ENTRY";

        for ($y = -$height; $y < ($height * 2); $y += $stepY) {
            for ($x = -$width; $x < ($width * 2); $x += $stepX) {
                // Faint slate shadow and white overlay
                @imagettftext($image, $fontSize, $angle, $x + 1, $y + 1, $shadowColor, $fontPath, $cleanWatermark);
                @imagettftext($image, $fontSize, $angle, $x, $y, $textColor, $fontPath, $cleanWatermark);
            }
        }
    }

    /**
     * Fallback standard watermark if FreeType/TTF is missing.
     */
    protected static function applyBasicWatermark($image, int $width, int $height, string $text): void
    {
        // 10% opacity in GD
        $textColor = imagecolorallocatealpha($image, 255, 255, 255, 114);
        $shadowColor = imagecolorallocatealpha($image, 0, 0, 0, 120);

        $stepX = max(380, (int) round($width / 2));
        $stepY = max(260, (int) round($height / 2));

        for ($y = 40; $y < $height; $y += $stepY) {
            for ($x = 40; $x < $width; $x += $stepX) {
                imagestring($image, 4, $x + 1, $y + 1, "NOKSHA PREVIEW", $shadowColor);
                imagestring($image, 4, $x, $y, "NOKSHA PREVIEW", $textColor);
            }
        }
    }
}
