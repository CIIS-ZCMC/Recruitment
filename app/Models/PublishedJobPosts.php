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
        'close_date',
        'close_time',
        'max_applicants',
    ];
}
