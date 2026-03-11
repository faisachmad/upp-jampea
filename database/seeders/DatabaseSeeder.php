<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Seed master data
        $this->call([
            PelabuhanSeeder::class,
            JenisPelayaranSeeder::class,
            KapalSeeder::class,
            BarangB3Seeder::class,
        ]);

        // Create default admin user
        User::factory()->create([
            'name' => 'Admin UPP Jampea',
            'email' => 'admin@uppjampea.id',
        ]);
    }
}
