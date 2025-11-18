<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ApplicantFiles extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'applicant_id',
        'job_post_files_id',
        'file_name',
        'file_path',
        'file_type',
        'file_size',
    ];
}
