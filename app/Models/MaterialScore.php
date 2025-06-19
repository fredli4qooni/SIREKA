<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaterialScore extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'student_id',
        'summative_material_id',
        'test_score',
        'nontest_score',
        'academic_year',
        'semester',
    ];

    // Opsional: Anda bisa menambahkan relasi di sini jika diperlukan nanti
    // public function student()
    // {
    //     return $this->belongsTo(Student::class);
    // }
    
    // public function summativeMaterial()
    // {
    //     return $this->belongsTo(SummativeMaterial::class);
    // }
}