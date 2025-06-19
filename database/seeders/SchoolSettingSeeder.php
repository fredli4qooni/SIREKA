<?php

namespace Database\Seeders;

use App\Models\SchoolSetting;
use Illuminate\Database\Seeder;
// Hapus use Illuminate\Support\Facades\DB; jika tidak dipakai
// Hapus use Illuminate\Database\Console\Seeds\WithoutModelEvents; jika tidak dipakai

class SchoolSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Pastikan menggunakan Model, bukan DB::table()
        SchoolSetting::truncate(); 

        SchoolSetting::create([
            // Isi hanya dengan kolom yang ADA di migrasi baru
            'school_name'   => 'SD Negeri Contoh',
            'npsn'          => '12345678',
            'academic_year' => '2023/2024',
            'semester' => 1,
            'report_place'  => 'Jakarta', // Dulu 'city', sekarang 'report_place'
            'report_date'   => now()->toDateString(),
            // Kolom lain seperti headmaster_name, dll, sekarang nullable jadi tidak wajib diisi di seeder
        ]);
    }
}