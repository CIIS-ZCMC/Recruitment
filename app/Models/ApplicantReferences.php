<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApplicantReferences extends Model
{
    protected $fillable = [
        'applicant_id',
        'fullname',
        'office_address',
        'contact_information',
    ];
}
