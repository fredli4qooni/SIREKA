<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany; // <-- Tambahkan ini

class Student extends Model
{
    use HasFactory;

    // Tambahkan properti $fillable untuk mengizinkan Mass Assignment
    protected $fillable = [
        'nis',
        'nisn',
        'name',
        'gender',
        'birth_place',
        'birth_date',
        'religion',
        'previous_education',
        'address',
        'father_name',
        'mother_name',
        'father_job',
        'mother_job',
        'parent_address_street',
        'parent_address_village',
        'parent_address_sub_district',
        'parent_address_district',
        'parent_address_province',
        'guardian_name',
        'guardian_job',
        'guardian_address',
        'guardian_phone',
        'attendance_sick',
        'attendance_permission',
        'attendance_alpha'
    ];


    /**
     * Mendefinisikan relasi "satu ke banyak" ke LearningOutcome.
     */
    public function learningOutcomes(): HasMany
    {
        return $this->hasMany(LearningOutcome::class);
    }

    /**
     * Mendefinisikan relasi "satu ke banyak" ke P5Project.
     */
    public function p5Projects(): HasMany
    {
        return $this->hasMany(P5Project::class);
    }

    public function achievedGoals()
    {
        return $this->belongsToMany(LearningGoal::class, 'achieved_goals')->withTimestamps();
    }

    // app/Models/Student.php
    public function extracurriculars()
    {
        return $this->hasMany(StudentExtracurricular::class);
    }
}
