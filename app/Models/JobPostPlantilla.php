<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class JobPostPlantilla extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'job_post_id',
        'plantilla_no',
        'salary_grade',
        'salary',
    ];
}
