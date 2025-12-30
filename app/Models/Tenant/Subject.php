<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Subject extends Model
{
    use HasFactory;
    protected $connection = 'tenant';

    protected $fillable = [
        'class_courses_id',
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
     * Subject belongs to a Class/Course
     */
    public function classCourse()
    {
        return $this->belongsTo(ClassCourse::class, 'class_courses_id');
    }
    public function scopeActive($query)
    {
        return $query->where('status', true);
    }
    public function courses()
    {
        return $this->belongsToMany(
            Course::class,
            'course_subject'
        );
    }
}
