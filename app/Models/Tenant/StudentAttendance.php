<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class StudentAttendance extends Model
{
    use HasFactory;

    protected $connection = 'tenant';

    protected $fillable = [
        'student_id',
        'class_course_id',
        'subject_id',
        'date',
        'status',
        'marked_by',
        'remarks',
    ];

    /**
     * Get the student who belongs to the attendance.
     */
    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    /**
     * Get the class course.
     */
    public function classCourse()
    {
        return $this->belongsTo(ClassCourse::class, 'class_course_id');
    }

    /**
     * Get the subject.
     */
    public function subject()
    {
        return $this->belongsTo(Subject::class, 'subject_id');
    }

    /**
     * Get the user who marked this attendance.
     */
    public function markedBy()
    {
        return $this->belongsTo(User::class, 'marked_by');
    }
}
