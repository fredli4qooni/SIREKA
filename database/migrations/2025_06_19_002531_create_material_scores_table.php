<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    // database/migrations/xxxx_xx_xx_xxxxxx_create_material_scores_table.php
    public function up(): void
    {
        Schema::create('material_scores', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->onDelete('cascade');
            $table->foreignId('summative_material_id')->constrained()->onDelete('cascade');
            $table->unsignedTinyInteger('test_score')->nullable(); // Nilai 0-100
            $table->unsignedTinyInteger('nontest_score')->nullable();
            $table->string('academic_year');
            $table->unsignedTinyInteger('semester'); // 1 atau 2
            $table->timestamps();
            $table->unique(
                ['student_id', 'summative_material_id', 'academic_year', 'semester'],
                'student_material_semester_unique'
            );
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('material_scores');
    }
};
