<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    // database/migrations/xxxx_xx_xx_xxxxxx_create_student_extracurriculars_table.php
    public function up(): void
    {
        Schema::create('student_extracurriculars', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->onDelete('cascade');
            $table->foreignId('extracurricular_id')->constrained()->onDelete('cascade');
            $table->string('academic_year');
            $table->string('semester');
            $table->enum('predicate', [
                'Mulai Berkembang',
                'Cukup Berkembang',
                'Berkembang',
                'Sangat Berkembang'
            ]);
            $table->text('description')->nullable(); // Deskripsi capaian
            $table->timestamps();

            $table->unique(
                ['student_id', 'extracurricular_id', 'academic_year', 'semester'],
                'student_extracurricular_unique' // Nama constraint pendek
            );
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_extracurriculars');
    }
};
