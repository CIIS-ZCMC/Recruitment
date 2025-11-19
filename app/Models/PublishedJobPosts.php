<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PublishedJobPosts extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'job_post_id',
        'published_date',
        'closing_date',
        'closing_time',
        'max_applicants',
    ];
}
