<?php

namespace Database\Seeders;

use App\Models\User; // <-- Gunakan model User
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash; // <-- Tambahkan ini untuk enkripsi password

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::truncate(); // Hapus user lama

        User::create([
            'name' => 'Guru Wali Kelas 4',
            'email' => 'mjumhadi@sdn1sdl.ac.id',
            'password' => Hash::make('walikelas4'), // passwordnya adalah 'password'
        ]);
    }
}