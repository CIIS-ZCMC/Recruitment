<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApplicantInformation extends Model
{
    protected $fillable = [
        'applicant_id',
        'last_name',
        'first_name',
        'middle_name',
        'civil_status',
        'blood_type',
        'religion',
        'citizenship',
        'sex',
        'citizenship',
        'place_of_birth',
        'date_of_birth',
        'resident_address',
        'residential_phone',
        'residential_zipcode',
        'permanent_address',
        'permanent_phone',
        'permanent_zipcode',
    ];

    public function fullName()
    {
        return $this->first_name . ' ' . $this->middle_name . ' ' . $this->last_name;
    }
}
