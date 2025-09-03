<?php

namespace Database\Seeders;

use App\Models\TblPasien;
use App\Models\TblRumahSakit;
use App\Models\User;
// ...existing code...
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Agus Wijaya',
            'username' => 'admin123',
            'role' => 'admin',
            'email' => 'wijaya@gmail.com',
            'email_verified_at' => now(),
            'password' => bcrypt('1234'),
        ]);

        User::factory()->create([
            'name' => 'Budi',
            'username' => 'user123',
            'role' => 'user',
            'email' => 'budi@gmail.com',
            'email_verified_at' => now(),
            'password' => bcrypt('1234'),
        ]);

        TblRumahSakit::factory(30)->create();
        TblPasien::factory(100)->create();



    }
}
