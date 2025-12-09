<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApplicantTraining extends Model
{
    protected $fillable = [
        'applicant_id',
        'name_of_training',
        'date_from',
        'date_to',
        'no_of_hours',
        'type_of_training',
        'sponsored_by',
    ];
}
