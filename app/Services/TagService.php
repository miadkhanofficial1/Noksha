<?php

namespace App\Services;

class TagService
{
    /**
     * Configurable keyword dictionary for auto-tagging.
     */
    protected static array $dictionary = [
        'ui' => ['interface', 'dashboard', 'app', 'mobile', 'screen', 'ui-kit', 'ux'],
        'fintech' => ['banking', 'finance', 'money', 'crypto', 'wallet', 'payment', 'card'],
        'logo' => ['branding', 'identity', 'vector', 'symbol', 'icon', 'minimalist'],
        'social' => ['instagram', 'facebook', 'post', 'story', 'media', 'marketing'],
        'poster' => ['flyer', 'event', 'banner', 'print', 'promo', 'poster-design'],
        '3d' => ['mockup', 'render', 'blender', 'illustration', 'spatial', '3d-asset'],
        'web' => ['landing-page', 'website', 'template', 'html', 'responsive', 'web-design'],
        'figma' => ['sketch', 'vector', 'component', 'wireframe', 'design-system'],
        'ecommerce' => ['shop', 'store', 'checkout', 'cart', 'product', 'online-shop'],
    ];

    /**
     * Common stop words to ignore during tagging.
     */
    protected static array $stopWords = [
        'the', 'is', 'a', 'an', 'and', 'or', 'in', 'on', 'for', 'with', 'by', 'at',
        'to', 'from', 'of', 'this', 'that', 'it', 'your', 'my', 'are', 'be', 'has',
        'have', 'best', 'new', 'free', 'premium', 'high', 'quality', 'modern', 'clean'
    ];

    /**
     * Generate smart AI-inspired tags from title, description, category, and manual input.
     *
     * @param string $title
     * @param string $description
     * @param string|null $categoryName
     * @param array $manualTags
     * @return array
     */
    public static function generate(string $title, string $description, ?string $categoryName = null, array $manualTags = []): array
    {
        $tags = [];

        // 1. Add manual tags first
        foreach ($manualTags as $mt) {
            $cleaned = strtolower(trim($mt));
            if (!empty($cleaned)) {
                $tags[] = $cleaned;
            }
        }

        // 2. Prepare text blob
        $combinedText = strtolower("{$title} {$description} " . ($categoryName ?? ''));
        preg_match_all('/[a-z0-9\-]+/i', $combinedText, $matches);
        $words = $matches[0] ?? [];

        // 3. Match against dictionary
        foreach (self::$dictionary as $categoryKey => $synonyms) {
            if (str_contains($combinedText, $categoryKey)) {
                $tags[] = $categoryKey;
            }

            foreach ($synonyms as $syn) {
                if (str_contains($combinedText, $syn)) {
                    $tags[] = $syn;
                    $tags[] = $categoryKey; // Auto-include parent keyword
                }
            }
        }

        // 4. Extract meaningful words from Title
        preg_match_all('/[a-z0-9\-]+/i', strtolower($title), $titleMatches);
        $titleWords = $titleMatches[0] ?? [];
        foreach ($titleWords as $word) {
            if (strlen($word) >= 3 && !in_array($word, self::$stopWords) && !is_numeric($word)) {
                $tags[] = $word;
            }
        }

        // 5. Clean, deduplicate, and limit to top tags
        $cleaned = array_map('trim', $tags);
        $unique = array_values(array_unique(array_filter($cleaned)));

        return array_slice($unique, 0, 10);
    }
}
