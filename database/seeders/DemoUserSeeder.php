<?php

namespace Database\Seeders;

use App\Models\SellerVerification;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DemoUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Super Admin Account
        User::updateOrCreate(
            ['email' => 'admin@noksha.com'],
            [
                'name' => 'Super Admin',
                'username' => 'admin',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'email_verified_at' => now(),
                'status' => 'active',
                'is_verified' => true,
                'contributor_status' => 'approved',
                'trust_score' => 100.00,
                'bio' => 'System Super Administrator for Noksha marketplace.',
            ]
        );

        // 2. Verified Seller / Contributor Demo Account
        $seller = User::updateOrCreate(
            ['email' => 'seller@noksha.com'],
            [
                'name' => 'Seller Demo',
                'username' => 'seller',
                'password' => Hash::make('password'),
                'role' => 'seller',
                'email_verified_at' => now(),
                'status' => 'active',
                'is_verified' => true,
                'contributor_status' => 'approved',
                'trust_score' => 98.50,
                'bio' => 'Professional graphics template contributor & UI/UX designer.',
            ]
        );

        // Ensure Seller KYC Record exists and is approved
        SellerVerification::updateOrCreate(
            ['user_id' => $seller->id],
            [
                'full_name' => 'Seller Demo',
                'date_of_birth' => '1998-05-15',
                'country' => 'Bangladesh',
                'id_type' => 'nid',
                'id_file_path' => 'verifications/demo_nid.jpg',
                'selfie_file_path' => 'verifications/demo_selfie.jpg',
                'status' => 'approved',
                'admin_notes' => 'Verified demo seller account.',
            ]
        );

        // 3. Standard Buyer Demo Account
        User::updateOrCreate(
            ['email' => 'buyer@noksha.com'],
            [
                'name' => 'Buyer Demo',
                'username' => 'buyer',
                'password' => Hash::make('password'),
                'role' => 'buyer',
                'email_verified_at' => now(),
                'status' => 'active',
                'is_verified' => false,
                'contributor_status' => 'none',
                'trust_score' => 100.00,
                'bio' => 'Graphic design template buyer and creative enthusiast.',
            ]
        );
    }
}
