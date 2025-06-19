<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

// app/Models/Extracurricular.php
class Extracurricular extends Model {
    use HasFactory;
    protected $fillable = ['name'];
}
