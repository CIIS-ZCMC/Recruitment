<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class JobPosts extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'title',
        'description',
    ];

    public function plantilla()
    {
        return $this->belongsTo(JobPostPlantilla::class, 'job_post_id', 'id');
    }

    public function qualifications()
    {
        return $this->hasOne(JobPostQualifications::class, 'job_post_id', 'id');
    }

    public function required_files()
    {
        return $this->hasMany(JobPostFiles::class, 'job_post_id', 'id');
    }

    public function status()
    {
        return $this->hasOne(JobPostStatus::class, 'job_post_id', 'id');
    }

    public function published()
    {
        return $this->hasOne(PublishedJobPosts::class, 'job_post_id', 'id');
    }
}
