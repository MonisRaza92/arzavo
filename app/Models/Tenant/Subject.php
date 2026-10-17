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
        'teacher_id',
        'image',
        'name',
        'slug',
        'description',
        'start_time',
        'end_time',
        'days',
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

    /**
     * Assigned Subject Teacher / Faculty
     */
    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    public function batches()
    {
        return $this->hasMany(Batch::class, 'subject_id');
    }

    public function batchSubjects()
    {
        return $this->hasMany(BatchSubject::class, 'subject_id');
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
