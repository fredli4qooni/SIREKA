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
        Schema::create('achieved_goals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->onDelete('cascade');
            $table->foreignId('learning_goal_id')->constrained()->onDelete('cascade');
            $table->string('academic_year');
            $table->unsignedTinyInteger('semester'); // 1 atau 2
            $table->timestamps();
            // Pastikan kombinasi unik untuk menghindari data ganda
            $table->unique(
                ['student_id', 'learning_goal_id', 'academic_year', 'semester'],
                'student_goal_semester_unique' // <-- Beri nama yang pendek & jelas
            );
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('achieved_goals');
    }
};
