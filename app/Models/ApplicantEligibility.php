<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApplicantEligibility extends Model
{
    protected $fillable = [
        'applicant_id',
        'eligibility',
        'rating',
        'date_of_exam',
        'place_of_examination',
        'license_no',
        'released_date',
        'date_of_validity'
    ];
}
