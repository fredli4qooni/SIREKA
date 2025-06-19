<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema; // <-- TAMBAHKAN INI

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Nonaktifkan pengecekan foreign key
        Schema::disableForeignKeyConstraints();

        // Panggil seeder-seeder kita
        $this->call([
            UserSeeder::class,
            SchoolSettingSeeder::class,
            SubjectSeeder::class,
            StudentSeeder::class,
        ]);

        // Aktifkan kembali pengecekan foreign key
        Schema::enableForeignKeyConstraints();
    }
}