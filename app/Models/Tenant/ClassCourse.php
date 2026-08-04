<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ClassCourse extends Model
{
    use HasFactory;
    protected $connection = 'tenant';
    protected $table = 'class_courses';

    protected $fillable = [
        'academic_category_id',
        'image',
        'name',
        'slug',
        'description',
        'order',
        'status',
    ];

    protected $casts = [
        'status' => 'boolean',
    ];

    /**
     * A Class/Course belongs to a Category
     */
    public function academicCategory()
    {
        return $this->belongsTo(AcademicCategory::class, 'academic_category_id');
    }

    /**
     * A Class/Course has many subjects
     */
    public function subjects()
    {
        return $this->hasMany(Subject::class, 'class_courses_id')->orderBy('order');
    }
    public function attendances()
    {
        return $this->hasMany(StudentAttendance::class, 'class_course_id');
    }
    public function scopeActive($query)
    {
        return $query->where('status', true);
    }
    public function courses()
    {
        return $this->belongsToMany(
            Course::class,
            'course_class_course'
        );
    }
}
