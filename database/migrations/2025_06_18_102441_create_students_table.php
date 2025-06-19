<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('students', function (Blueprint $table) {
            $table->id();

            // Data Pribadi Siswa
            $table->string('nis')->unique();
            $table->string('nisn')->nullable()->unique();
            $table->string('name');
            $table->enum('gender', ['L', 'P']);
            $table->string('birth_place')->nullable();
            $table->date('birth_date')->nullable();
            $table->string('religion')->nullable();
            $table->string('previous_education')->nullable();

            // Alamat Siswa
            $table->string('address')->nullable();

            // Data Orang Tua
            $table->string('father_name')->nullable();
            $table->string('mother_name')->nullable();
            $table->string('father_job')->nullable();
            $table->string('mother_job')->nullable();

            // Alamat Orang Tua
            $table->string('parent_address_street')->nullable();
            $table->string('parent_address_village')->nullable();
            $table->string('parent_address_sub_district')->nullable();
            $table->string('parent_address_district')->nullable();
            $table->string('parent_address_province')->nullable();

            // Data Wali (jika berbeda dari orang tua)
            $table->string('guardian_name')->nullable();
            $table->string('guardian_job')->nullable();
            $table->string('guardian_address')->nullable();
            $table->string('guardian_phone')->nullable();

            // Kehadiran (Absensi) - akan kita isi nanti, untuk sekarang kita siapkan kolomnya
            $table->integer('attendance_sick')->default(0);
            $table->integer('attendance_permission')->default(0);
            $table->integer('attendance_alpha')->default(0);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
