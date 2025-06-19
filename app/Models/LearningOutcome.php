<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LearningOutcome extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'student_id',
        'subject_id',
        'academic_year',
        'semester',              // <-- Tambahkan
        'final_test_score',      // <-- Tambahkan
        'final_nontest_score',   // <-- Tambahkan
        'report_score',          // <-- Tambahkan
        'description',
    ];

    /**
     * Mendefinisikan relasi "milik" ke Student.
     */
    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    /**
     * Mendefinisikan relasi "milik" ke Subject.
     */
    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }
}