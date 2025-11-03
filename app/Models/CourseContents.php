<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CourseContents extends Model
{
    protected $fillable = [
        'course_id', 'content_id', 'order', 'is_required', 'is_locked'
    ];

    public function course()
    {
        return $this->belongsTo(Courses::class, 'course_id');
    }
    public function content()
    {
        return $this->belongsTo(Contents::class, 'content_id');
    }
}
