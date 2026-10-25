<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Resource;
use App\Models\SellerVerification;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

class AssetSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Ensure or provision the verified seller account (MIAD KHAN)
        $seller = User::where('email', 'miadkhan.official1@gmail.com')->first();

        if (!$seller) {
            $seller = User::where('role', 'seller')->first();
        }

        $sellerAttributes = [
            'name' => 'MIAD KHAN',
            'email' => 'miadkhan.official1@gmail.com',
            'role' => 'seller',
            'is_admin' => false,
            'is_contributor' => true,
            'contributor_status' => 'approved',
            'status' => 'active',
            'is_verified' => true,
            'email_verified_at' => now(),
            'headline' => 'Lead UI/UX Designer & Verified Creator',
            'bio' => 'Senior product designer specializing in FinTech, design systems, and mobile UI kits.',
            'trust_score' => 99.0,
        ];

        if (Schema::hasColumn('users', 'active_mode')) {
            $sellerAttributes['active_mode'] = 'seller';
        }

        if (!$seller) {
            $sellerAttributes['username'] = 'miadkhan';
            $sellerAttributes['password'] = Hash::make('seller123456');
            $seller = User::create($sellerAttributes);
        } else {
            // Synchronize verified seller attributes
            $seller->update($sellerAttributes);
        }

        // Ensure Seller Verification (KYC) record exists & approved
        if (class_exists(SellerVerification::class)) {
            SellerVerification::updateOrCreate(
                ['user_id' => $seller->id],
                [
                    'full_name' => $seller->name,
                    'country' => 'Bangladesh',
                    'id_type' => 'nid',
                    'status' => 'approved',
                    'admin_notes' => 'Verified official creator profile.',
                ]
            );
        }

        // 2. Ensure Marketplace Categories exist
        $catUiKit = Category::firstOrCreate(
            ['slug' => 'ui-kit'],
            ['name' => 'UI Kit', 'icon' => 'bi-layers-fill']
        );

        $catMockups = Category::firstOrCreate(
            ['slug' => 'mockups'],
            ['name' => 'Mockups', 'icon' => 'bi-box']
        );

        $catTemplates = Category::firstOrCreate(
            ['slug' => 'templates'],
            ['name' => 'Templates', 'icon' => 'bi-window']
        );

        // 3. Define 3 Verified Sample Marketplace Assets
        $assets = [
            [
                'title' => 'Modern FinTech Mobile App UI Kit',
                'slug' => 'modern-fintech-mobile-app-ui-kit',
                'category_id' => $catUiKit->id,
                'description' => 'A comprehensive, pixel-perfect FinTech mobile banking UI kit crafted in Figma. Includes 80+ clean responsive screens, dark & light themes, design tokens, interactive prototypes, and modular design components.',
                'preview_image' => 'https://images.unsplash.com/photo-1618005182384-a83a8bd57fbe?w=1200&q=80',
                'file_path' => 'resources/demo_fintech_app.zip',
                'file_type' => 'FIGMA',
                'tags' => ['fintech', 'banking', 'mobile', 'ui kit', 'figma', 'dark mode'],
                'is_paid' => true,
                'price' => 500.00,
                'requirements' => 'Figma 2024+, Inter Font Family',
                'demo_link' => 'https://www.figma.com',
                'status' => 'approved',
                'downloads' => 142,
                'views' => 1820,
            ],
            [
                'title' => 'Minimalist E-Commerce Branding Mockup Kit',
                'slug' => 'minimalist-ecommerce-branding-mockup-kit',
                'category_id' => $catMockups->id,
                'description' => 'Photorealistic 3D stationery and packaging mockup collection. Features customizable smart layers, realistic ambient shadows, studio lighting scenes, and high-resolution 4K rendering.',
                'preview_image' => 'https://images.unsplash.com/photo-1558655146-d09347e92766?w=1200&q=80',
                'file_path' => 'resources/demo_branding_mockup.zip',
                'file_type' => 'PSD',
                'tags' => ['mockup', 'branding', 'packaging', '3d', 'photoshop', 'minimal'],
                'is_paid' => true,
                'price' => 350.00,
                'requirements' => 'Adobe Photoshop CC 2022+ with Smart Object support',
                'demo_link' => null,
                'status' => 'approved',
                'downloads' => 96,
                'views' => 1150,
            ],
            [
                'title' => 'Corporate Business Pitch Deck Presentation',
                'slug' => 'corporate-business-pitch-deck-presentation',
                'category_id' => $catTemplates->id,
                'description' => 'Clean, investor-ready corporate pitch deck template containing 45+ unique slide layouts, financial data charts, infographic diagrams, and team showcase slides.',
                'preview_image' => 'https://images.unsplash.com/photo-1542744094-3a31f272c490?w=1200&q=80',
                'file_path' => 'resources/demo_pitch_deck.zip',
                'file_type' => 'FIGMA',
                'tags' => ['pitch deck', 'presentation', 'corporate', 'business', 'free'],
                'is_paid' => false,
                'price' => 0.00,
                'requirements' => 'Figma or PowerPoint',
                'demo_link' => null,
                'status' => 'approved',
                'downloads' => 280,
                'views' => 2450,
            ],
        ];

        foreach ($assets as $asset) {
            $data = [
                'user_id' => $seller->id,
                'category_id' => $asset['category_id'],
                'title' => $asset['title'],
                'description' => $asset['description'],
                'preview_image' => $asset['preview_image'],
                'file_path' => $asset['file_path'],
                'file_type' => $asset['file_type'],
                'tags' => $asset['tags'],
                'is_paid' => $asset['is_paid'],
                'price' => $asset['price'],
                'requirements' => $asset['requirements'],
                'demo_link' => $asset['demo_link'],
                'status' => $asset['status'],
                'downloads' => $asset['downloads'],
                'views' => $asset['views'],
            ];

            if (Schema::hasColumn('resources', 'is_active')) {
                $data['is_active'] = true;
            }

            Resource::updateOrCreate(
                ['slug' => $asset['slug']],
                $data
            );
        }

        $this->command?->info('Sample verified digital assets provisioned successfully.');
    }
}
