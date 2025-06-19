<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    // database/migrations/xxxx_xx_xx_xxxxxx_create_learning_outcomes_table.php
    public function up(): void
    {
        Schema::create('learning_outcomes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->onDelete('cascade');
            $table->foreignId('subject_id')->constrained()->onDelete('cascade');
            $table->string('academic_year');
            $table->unsignedTinyInteger('semester')->default(1); // 1 atau 2

            // Nilai Sumatif Akhir Semester
            $table->unsignedTinyInteger('final_test_score')->nullable();
            $table->unsignedTinyInteger('final_nontest_score')->nullable();

            // Nilai Rapor (hasil rata-rata) dan Deskripsi
            $table->decimal('report_score', 5, 2)->nullable(); // Nilai rapor, cth: 85.50
            $table->text('description')->nullable(); // Deskripsi capaian (opsional, bisa digenerate)

            $table->timestamps();
            // Beri nama yang pendek dan jelas
            $table->unique(
                ['student_id', 'subject_id', 'academic_year', 'semester'],
                'student_subject_semester_unique'
            );
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('learning_outcomes');
    }
};
