<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApplicantVoluntaryWork extends Model
{
    protected $fillable = [
        'applicant_id',
        'name_of_organization',
        'date_from',
        'date_to',
        'no_of_hours',
        'position',
    ];
}
