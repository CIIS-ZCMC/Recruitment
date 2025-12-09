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
}
