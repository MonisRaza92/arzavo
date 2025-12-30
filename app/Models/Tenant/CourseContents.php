<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CourseContents extends Model
{
    use HasFactory;
    protected $connection = 'tenant';
    protected $fillable = [
        'course_id', 'content_id', 'order', 'is_required', 'is_locked'
    ];

    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id');
    }
    public function content()
    {
        return $this->belongsTo(Content::class, 'content_id');
    }
}
