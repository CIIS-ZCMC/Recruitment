<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CalendarSchedules extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'application_id',
        'date',
        'time',
        'venue',
        'interviewer',
        'remarks',
    ];
}
