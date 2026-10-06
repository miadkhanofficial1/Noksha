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
            ['name' => 'Graphics', 'slug' => 'graphics', 'icon' => 'bi-palette'],
            ['name' => 'UI Kit', 'slug' => 'ui-kit', 'icon' => 'bi-layers-fill'],
            ['name' => 'Mockups', 'slug' => 'mockups', 'icon' => 'bi-box'],
            ['name' => 'Templates', 'slug' => 'templates', 'icon' => 'bi-window'],
            ['name' => 'Illustrations', 'slug' => 'illustrations', 'icon' => 'bi-stars'],
            ['name' => 'Icons', 'slug' => 'icons', 'icon' => 'bi-vector-pen'],
            ['name' => 'Fonts', 'slug' => 'fonts', 'icon' => 'bi-fonts'],
        ];

        foreach ($categories as $cat) {
            Category::updateOrCreate(
                ['slug' => $cat['slug']],
                [
                    'name' => $cat['name'],
                    'icon' => $cat['icon'],
                ]
            );
        }
    }
}
