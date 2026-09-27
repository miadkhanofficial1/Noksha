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
     */
    protected static function applyTtfWatermark($image, int $width, int $height, string $text, string $fontPath): void
    {
        $fontSize = max(14, (int) round($width / 40));
        $angle = 32;

        // Semi-transparent colors
        // Alpha range in GD: 0 (opaque) to 127 (completely transparent)
        $shadowColor = imagecolorallocatealpha($image, 15, 23, 42, 85); // Dark slate with alpha
        $textColor = imagecolorallocatealpha($image, 255, 255, 255, 55);  // Translucent white

        // Grid spacing based on image dimensions
        $stepX = max(260, (int) round($width / 3.5));
        $stepY = max(180, (int) round($height / 4.5));

        for ($y = -$height; $y < ($height * 2); $y += $stepY) {
            for ($x = -$width; $x < ($width * 2); $x += $stepX) {
                // Subtle shadow for legibility over both dark and light artworks
                @imagettftext($image, $fontSize, $angle, $x + 2, $y + 2, $shadowColor, $fontPath, $text);
                @imagettftext($image, $fontSize, $angle, $x, $y, $textColor, $fontPath, $text);
            }
        }

        // Center prominent safety seal / badge
        $centerBoxW = min((int)($width * 0.85), 650);
        $centerBoxH = (int) round($fontSize * 3.2);
        $centerX = (int) round(($width - $centerBoxW) / 2);
        $centerY = (int) round(($height - $centerBoxH) / 2);

        $badgeBg = imagecolorallocatealpha($image, 15, 23, 42, 60); // 50% dark translucent bar
        $badgeBorder = imagecolorallocatealpha($image, 255, 255, 255, 70);
        $badgeText = imagecolorallocatealpha($image, 255, 255, 255, 30);

        imagefilledrectangle($image, $centerX, $centerY, $centerX + $centerBoxW, $centerY + $centerBoxH, $badgeBg);
        imagerectangle($image, $centerX, $centerY, $centerX + $centerBoxW, $centerY + $centerBoxH, $badgeBorder);

        $badgeTitle = "NOKSHA CONTEST ENTRY • STRICTLY FOR PREVIEW ONLY";
        $badgeFontSize = max(12, (int) round($fontSize * 0.8));
        $bbox = @imagettfbbox($badgeFontSize, 0, $fontPath, $badgeTitle);
        $textW = $bbox ? abs($bbox[4] - $bbox[0]) : (strlen($badgeTitle) * 9);
        $textX = (int) round($centerX + (($centerBoxW - $textW) / 2));
        $textY = (int) round($centerY + ($centerBoxH / 2) + ($badgeFontSize / 2));

        @imagettftext($image, $badgeFontSize, 0, $textX, $textY, $badgeText, $fontPath, $badgeTitle);
    }

    /**
     * Fallback standard watermark if FreeType/TTF is missing.
     */
    protected static function applyBasicWatermark($image, int $width, int $height, string $text): void
    {
        $textColor = imagecolorallocatealpha($image, 255, 255, 255, 60);
        $shadowColor = imagecolorallocatealpha($image, 0, 0, 0, 80);

        $stepX = 220;
        $stepY = 120;

        for ($y = 20; $y < $height; $y += $stepY) {
            for ($x = 20; $x < $width; $x += $stepX) {
                imagestring($image, 5, $x + 1, $y + 1, $text, $shadowColor);
                imagestring($image, 5, $x, $y, $text, $textColor);
            }
        }
    }
}
