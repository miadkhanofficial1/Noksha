<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\SellerVerification;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Seed essential marketplace taxonomy
        $this->call([
            CategorySeeder::class,
        ]);

        // 2. Provision ONLY ONE authoritative Super Admin account
        $email = 'noksha.official1@gmail.com';
        $username = 'noksha_admin';

        $attributes = [
            'name' => 'Noksha HQ Admin',
            'email' => $email,
            'password' => Hash::make('admin@noksha1234'),
            'role' => 'admin',
            'is_admin' => true,
            'status' => 'active',
            'is_verified' => true,
            'contributor_status' => 'approved',
            'email_verified_at' => now(),
            'trust_score' => 100.00,
            'active_mode' => 'buyer',
            'deleted_at' => null,
        ];

        if (Schema::hasColumn('users', 'username')) {
            $attributes['username'] = $username;
        }

        if (Schema::hasColumn('users', 'is_contributor')) {
            $attributes['is_contributor'] = true;
        }

        $admin = User::withTrashed()->updateOrCreate(
            ['email' => $email],
            $attributes
        );

        if ($admin->trashed()) {
            $admin->restore();
        }

        // Ensure KYC / Contributor Verification is verified & approved
        if (class_exists(SellerVerification::class)) {
            SellerVerification::updateOrCreate(
                ['user_id' => $admin->id],
                [
                    'full_name' => 'Noksha HQ Admin',
                    'date_of_birth' => '1990-01-01',
                    'country' => 'Bangladesh',
                    'id_type' => 'nid',
                    'id_file_path' => 'verifications/admin_nid.jpg',
                    'selfie_file_path' => 'verifications/admin_selfie.jpg',
                    'status' => 'approved',
                    'admin_notes' => 'Authoritative verified Super Admin profile.',
                ]
            );
        }

        $this->command?->info("Authoritative Super Admin provisioned: {$email}");
    }
}
