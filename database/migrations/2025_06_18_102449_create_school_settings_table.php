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
        Schema::create('school_settings', function (Blueprint $table) {
            $table->id();
            // Data Sekolah
            $table->string('school_name')->nullable();
            $table->string('npsn')->nullable();
            $table->string('nss')->nullable();
            $table->text('address')->nullable();
            $table->string('postal_code')->nullable();
            $table->string('village')->nullable();
            $table->string('sub_district')->nullable();
            $table->string('district')->nullable();
            $table->string('province')->nullable();
            $table->string('email')->nullable();

            // Data Kepala Sekolah
            $table->string('headmaster_name')->nullable();
            $table->string('headmaster_nip')->nullable();

            // Data Rapor
            $table->string('class_level')->default('4');
            $table->string('phase')->default('B');
            $table->unsignedTinyInteger('semester')->default(1); // 1 atau 2
            $table->string('academic_year')->nullable();
            $table->string('report_place')->nullable();
            $table->date('report_date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('school_settings');
    }
};
