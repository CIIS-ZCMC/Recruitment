<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Applicant extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'status',
        'password'
    ];

    public function personalInformation()
    {
        return $this->hasOne(ApplicantInformation::class);
    }

    public function educationalBackground()
    {
        return $this->hasMany(ApplicantEducation::class);
    }

    public function eligibilityRecords()
    {
        return $this->hasMany(ApplicantEligibility::class);
    }

    public function workExperiences()
    {
        return $this->hasMany(ApplicantExperiences::class);
    }

    public function voluntaryWork()
    {
        return $this->hasMany(ApplicantVoluntaryWork::class);
    }

    public function trainings()
    {
        return $this->hasMany(ApplicantTraining::class);
    }
}
