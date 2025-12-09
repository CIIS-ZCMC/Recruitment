<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApplicantEducation extends Model
{
    protected $fillable = [
        'applicant_id',
        'level',
        'school',
        'degree',
        'year_from',
        'year_to',
        'units_earned',
        'year_graduated',
        'honor_received',
    ];
}
