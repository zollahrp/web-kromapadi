<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        
        // Super Admin
        User::factory()->create([
            'name' => 'Super Admin',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('password'),
            'role' => 'super_admin',
        ]);

        // Wilayah Mulyaharja
        $wilayah = \App\Models\Wilayah::create([
            'name' => 'Mulyaharja',
        ]);

        // Master Wilayah (Pak Faisal)
        User::factory()->create([
            'name' => 'Master Wilayah Mulyaharja',
            'email' => 'wilayah@gmail.com',
            'password' => bcrypt('password'),
            'role' => 'master_wilayah',
            'wilayah_id' => $wilayah->id,
        ]);
    }
}
