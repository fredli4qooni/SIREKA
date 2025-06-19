<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SchoolSetting extends Model
{
    protected $fillable = [
        'school_name',
        'npsn',
        'nss',
        'address',
        'postal_code',
        'village',
        'sub_district',
        'district',
        'province',
        'email',
        'headmaster_name',
        'headmaster_nip',
        'class_level',
        'phase',
        'semester',
        'academic_year',
        'report_place',
        'report_date'
    ];
}
