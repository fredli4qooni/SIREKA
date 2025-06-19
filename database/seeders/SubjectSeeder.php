<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB; // <-- Jangan lupa tambahkan ini

class SubjectSeeder extends Seeder
{
    public function run(): void
    {
        // Hapus data lama untuk menghindari duplikasi saat seeding ulang
        DB::table('subjects')->delete();

        DB::table('subjects')->insert([
            ['name' => 'Pendidikan Agama & Budi Pekerti', 'order' => 1],
            ['name' => 'Pendidikan Pancasila', 'order' => 2],
            ['name' => 'Bahasa Indonesia', 'order' => 3],
            ['name' => 'Matematika', 'order' => 4],
            ['name' => 'Ilmu Pengetahuan Alam dan Sosial (IPAS)', 'order' => 5],
            ['name' => 'Pendidikan Jasmani, Olahraga, dan Kesehatan (PJOK)', 'order' => 6],
            ['name' => 'Seni Budaya (Seni Musik/Rupa/Tari/Teater)', 'order' => 7],
            ['name' => 'Bahasa Daerah', 'order' => 8],
        ]);
    }
}