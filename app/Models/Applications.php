<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Applications extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'applicant_id',
        'published_job_post_id',
        'status',
        'remarks',
    ];
}
