<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LessonProgress extends Model
{
    use HasFactory;
    protected $connection = 'tenant';
    protected $fillable = [
        'course_id',
        'lesson_id',
        'user_id',
        'is_completed',
        'watched_duration',
        'completed_at',
    ];
}

