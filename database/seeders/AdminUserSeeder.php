<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $existingUser = User::where('email', 'admin@noksha.com')->first();
        $username = $existingUser?->username ?? (User::where('username', 'admin')->exists() ? 'admin_' . time() : 'admin');

        $attributes = [
            'name' => 'Admin',
            'password' => Hash::make('admin123456'),
            'email_verified_at' => now(),
        ];

        if (Schema::hasColumn('users', 'username')) {
            $attributes['username'] = $username;
        }

        if (Schema::hasColumn('users', 'role')) {
            $attributes['role'] = 'admin';
        }

        if (Schema::hasColumn('users', 'is_admin')) {
            $attributes['is_admin'] = true;
        }

        if (Schema::hasColumn('users', 'status')) {
            $attributes['status'] = 'active';
        }

        if (Schema::hasColumn('users', 'is_verified')) {
            $attributes['is_verified'] = true;
        }

        if (Schema::hasColumn('users', 'contributor_status')) {
            $attributes['contributor_status'] = 'approved';
        }

        User::updateOrCreate(
            ['email' => 'admin@noksha.com'],
            $attributes
        );

        $this->command?->info('Admin user provisioned successfully: admin@noksha.com');
    }
}
