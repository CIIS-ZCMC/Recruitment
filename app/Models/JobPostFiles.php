<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class JobPostFiles extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'job_post_id',
        'file_type',
        'file_name',
        'is_required',
    ];
}
