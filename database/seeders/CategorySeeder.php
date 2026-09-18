<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['name' => 'UI Kits', 'slug' => 'ui-kits', 'icon' => 'bi-layers-fill'],
            ['name' => 'Logos & Vectors', 'slug' => 'logos-vectors', 'icon' => 'bi-vector-pen'],
            ['name' => 'Social Media Templates', 'slug' => 'social-media-templates', 'icon' => 'bi-instagram'],
            ['name' => 'Posters & Flyers', 'slug' => 'posters-flyers', 'icon' => 'bi-image'],
            ['name' => 'Branding & Identity', 'slug' => 'branding-identity', 'icon' => 'bi-palette'],
            ['name' => 'Web Design Systems', 'slug' => 'web-design-systems', 'icon' => 'bi-window'],
            ['name' => '3D Mockups', 'slug' => '3d-mockups', 'icon' => 'bi-box'],
            ['name' => 'Illustrations', 'slug' => 'illustrations', 'icon' => 'bi-stars'],
        ];

        foreach ($categories as $cat) {
            Category::updateOrCreate(
                ['slug' => $cat['slug']],
                ['name' => $cat['name'], 'icon' => $cat['icon']]
            );
        }
    }
}
