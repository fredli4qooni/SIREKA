<?php

namespace Database\Seeders;

use App\Models\Student; // <-- Gunakan model Student
use Illuminate\Database\Seeder;

class StudentSeeder extends Seeder
{
    public function run(): void
    {
        Student::truncate(); // Hapus siswa lama

        Student::create([
            'nis' => '1001',
            'nisn' => '0012345671',
            'name' => 'Ahmad Zaini',
            'gender' => 'L',
            'birth_place' => 'Jakarta',
            'birth_date' => '2014-05-10',
        ]);

        Student::create([
            'nis' => '1002',
            'nisn' => '0012345672',
            'name' => 'Citra Lestari',
            'gender' => 'P',
            'birth_place' => 'Bandung',
            'birth_date' => '2014-08-15',
        ]);
    }
}
