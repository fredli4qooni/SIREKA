<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SummativeMaterial extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [ // <-- TAMBAHKAN INI
        'subject_id',
        'material_name',
        'order',
    ];

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }
}