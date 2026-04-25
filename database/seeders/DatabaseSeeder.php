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
        // Membersihkan data user agar tidak error saat restart seed
        User::truncate();

        User::factory()->create([
            'name' => 'Administrator Sistem',
            'email' => 'admin@healthmining.test',
            'password' => \Illuminate\Support\Facades\Hash::make('password')
        ]);

        User::factory()->create([
            'name' => 'Dokter/Perawat Spesialis',
            'email' => 'medis@healthmining.test',
            'password' => \Illuminate\Support\Facades\Hash::make('password')
        ]);
    }
}
