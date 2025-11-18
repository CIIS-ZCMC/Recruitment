<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class JobPostStatus extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'job_post_id',
        'no_of_vacancies',
        'place_of_assignment',
        'is_filled',
        'is_active',
    ];

    public function jobPost()
    {
        return $this->belongsTo(JobPosts::class);
    }
}
