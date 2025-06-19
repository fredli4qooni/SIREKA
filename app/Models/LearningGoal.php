<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LearningGoal extends Model
{
    use HasFactory;
    protected $fillable = ['subject_id', 'goal_description', 'scope'];
    public function subject() { return $this->belongsTo(Subject::class); }
}
