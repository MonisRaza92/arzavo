<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Course extends Model
{
    use HasFactory;
    protected $connection = 'tenant';

    protected $fillable = [
        'title',
        'slug',
        'description',
        'thumbnail',
        'video',
        'language',
        'level',
        'duration',
        'max_students',
        'is_paid',
        'price',
        'discount_price',
        'is_public',
        'requires_enrollment',
        'enable_modules',
        'enable_lessons',
        'enable_quizzes',
        'enable_assignments',
        'enable_certificates',
        'enable_reviews',
        'status',
        'publish_date',
        'expire_date',
        'total_enrollments',
        'total_reviews',
        'user_id',
    ];


    public function getRouteKeyName()
    {
        return 'slug';
    }

    /* ---------------------------------
     | RELATIONS
     |---------------------------------*/

    // Course belongs to creator / teacher
    public function author()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // MANY classes
    public function class()
    {
        return $this->belongsToMany(
            ClassCourse::class,
            'course_class_course'
        );
    }

    // MANY subjects
    public function subjects()
    {
        return $this->belongsToMany(
            Subject::class,
            'course_subject'
        );
    }

    // Modules (STEP I me use hoga)
    public function modules()
    {
        return $this->hasMany(CourseModule::class)
            ->orderBy('order');
    }

    // Direct lessons (agar modules disabled ho)
    public function lessons()
    {
        return $this->hasMany(CourseLesson::class)
            ->whereNull('course_module_id')
            ->orderBy('order');
    }

    /* ---------------------------------
     | SCOPES (FILTER SYSTEM READY)
     |---------------------------------*/

    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    public function scopePublic($query)
    {
        return $query->where('is_public', true);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }
    public function usesModules()
    {
        return (bool) $this->enable_modules;
    }
    // All lessons
    public function allLessons()
    {
        return $this->hasMany(CourseLesson::class)
            ->orderBy('order');
    }

    // Direct lessons (modules OFF)
    public function directLessons()
    {
        return $this->hasMany(CourseLesson::class)
            ->whereNull('course_module_id')
            ->orderBy('order');
    }
    public function teacher()
    {
        return $this->hasOne(User::class, 'user_id');
    }
}
