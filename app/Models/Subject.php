<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory; // Pastikan ini ada
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany; // <-- Tambahkan import ini

class Subject extends Model
{
    use HasFactory; // Pastikan ini juga ada

    protected $fillable = ['name', 'order'];

    /**
     * Mendefinisikan relasi "satu ke banyak" ke LearningOutcome.
     * Satu mata pelajaran bisa memiliki banyak entri nilai.
     */
    public function learningOutcomes(): HasMany
    {
        return $this->hasMany(LearningOutcome::class);
    }

    /**
     * Kita juga bisa tambahkan relasi ke LearningGoal yang akan kita gunakan nanti.
     */
    public function learningGoals(): HasMany
    {
        return $this->hasMany(LearningGoal::class);
    }

    public function summativeMaterials(): HasMany
    {
        return $this->hasMany(SummativeMaterial::class)->orderBy('order');
    }
}