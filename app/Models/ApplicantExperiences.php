<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApplicantExperiences extends Model
{
    protected $fillable = [
        'applicant_id',
        'date_from',
        'date_to',
        'position',
        'agency_company',
        'status_of_appointment',
        'government_service',
    ];
}
