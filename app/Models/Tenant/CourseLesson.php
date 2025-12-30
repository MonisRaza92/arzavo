<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CourseLesson extends Model
{
    use HasFactory;
    protected $connection = 'tenant';

    protected $fillable = [
        'course_id',
        'course_module_id',
        'title',
        'description',
        'type',
        'video_path',
        'file_path',
        'content',
        'is_free',
        'is_active',
        'is_mandatory',
        'duration',
        'order',
    ];

    /* -----------------------------
     | RELATIONS
     |-----------------------------*/

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function module()
    {
        return $this->belongsTo(CourseModule::class, 'course_module_id');
    }

    /* -----------------------------
     | SCOPES
     |-----------------------------*/

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeFree($query)
    {
        return $query->where('is_free', true);
    }
}
