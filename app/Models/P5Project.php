<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo; // <-- Tambahkan ini

class P5Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'academic_year',
        'project_name',
        'description',
    ];

    /**
     * Mendefinisikan relasi "milik" ke Student.
     */
    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }
}