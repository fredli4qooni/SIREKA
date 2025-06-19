<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

// app/Models/StudentExtracurricular.php
class StudentExtracurricular extends Model {
    use HasFactory;
    protected $fillable = [
        'student_id', 'extracurricular_id', 'academic_year', 'semester',
        'predicate', 'description'
    ];
    public function extracurricular() { return $this->belongsTo(Extracurricular::class); }
}
