<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;

class Admission extends Model
{
    protected $connection = 'tenant';
    protected $table = 'admissions';

    protected $fillable = [
        'user_id',
        'academic_category_id',
        'class_id',
        'subject_id',
        'aadhaar_number',
        'aadhaar_front',
        'aadhaar_back',
        'previous_marksheet',
        'previous_school',
        'previous_grade',
        'notes',
        'status',
        'admin_remarks',
        'applied_at',
        'approved_at',
    ];

    protected $casts = [
        'applied_at' => 'datetime',
        'approved_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function academicCategory()
    {
        return $this->belongsTo(AcademicCategory::class, 'academic_category_id');
    }

    public function classCourse()
    {
        return $this->belongsTo(ClassCourse::class, 'class_id');
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class, 'subject_id');
    }
}
