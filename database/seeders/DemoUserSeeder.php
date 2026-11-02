<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DemoUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Deprecated: Demo accounts removed to enforce a clean single-admin state.
     */
    public function run(): void
    {
        $this->command?->warn('DemoUserSeeder is deprecated and disabled.');
    }
}
