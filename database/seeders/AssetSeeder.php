<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class AssetSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Deprecated: Demo seller and assets removed to maintain clean single-admin state.
     */
    public function run(): void
    {
        $this->command?->warn('AssetSeeder is deprecated and disabled.');
    }
}
