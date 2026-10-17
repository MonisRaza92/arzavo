<?php

namespace App\Models\Tenant;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Tenant;
use Illuminate\Database\Eloquent\Model;

class User extends Authenticatable
{
    protected $connection = 'tenant';
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'banner',
        'profile_picture',
        'fname',
        'lname',
        'username',
        'headline',
        'designation',
        'qualification',
        'joining_date',
        'base_salary',
        'salary_type',
        'number',
        'email',
        'dob',
        'roll_number',
        'father_name',
        'mother_name',
        'parent_phone',
        'emergency_contact',
        'blood_group',
        'academic_category_id',
        'class_id',
        'subject_id',
        'batch_id',
        'aadhaar_number',
        'aadhaar_front',
        'aadhaar_back',
        'previous_marksheet',
        'previous_school',
        'address',
        'city',
        'state',
        'country',
        'pincode',
        'about',
        'password',
        'role',
        'status',
        'admission_status',
        'enrolled_at',
        'pending_profile_updates',
        'last_login',
        'email_verified_at',
        'remember_token',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'enrolled_at' => 'datetime',
            'joining_date' => 'date',
            'base_salary' => 'decimal:2',
            'password' => 'hashed',
        ];
    }

    public function staffAttendances()
    {
        return $this->hasMany(StaffAttendance::class, 'user_id');
    }

    public function payrolls()
    {
        return $this->hasMany(StaffPayroll::class, 'user_id');
    }

    public function salaryStructure()
    {
        return $this->hasOne(StaffSalaryStructure::class, 'user_id');
    }

    public function isStudent()
    {
        return $this->role === 'student';
    }

    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isUser()
    {
        return $this->role === 'user';
    }
    public function isTeacher()
    {
        return $this->role === 'teacher';
    }

    public function courses()
    {
        return $this->hasMany(Course::class, 'user_id');
    }

    public function contents()
    {
        return $this->hasMany(Content::class, 'user_id');
    }
    public function feePlans()
    {
        return $this->hasMany(FeePlans::class, 'student_id');
    }
    public function feePayments()
    {
        return $this->hasMany(FeePayments::class, 'student_id');
    }
    public function enrolledCourses()
    {
        return $this->belongsToMany(Course::class, 'course_enrollments', 'user_id', 'course_id')->withPivot('status', 'enrolled_at', 'completed_at');
    }

    public function attendances()
    {
        return $this->hasMany(StudentAttendance::class, 'student_id');
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class, 'subject_id');
    }

    public function directSubjects()
    {
        return $this->belongsToMany(Subject::class, 'student_subjects', 'student_id', 'subject_id')->withTimestamps();
    }

    public function class()
    { // "class" reserved word hai
        return $this->belongsTo(ClassCourse::class, 'class_id');
    }

    public function batch()
    {
        return $this->belongsTo(Batch::class, 'batch_id');
    }

    public function assignedBatches()
    {
        return $this->hasMany(Batch::class, 'teacher_id');
    }

    public function managedClasses()
    {
        return $this->hasMany(ClassCourse::class, 'class_teacher_id');
    }

    public function assignedSubjects()
    {
        return $this->hasMany(Subject::class, 'teacher_id');
    }
    public function globalUser()
    {
        return $this->belongsTo(
            \App\Models\Arzavo\User::class,
            'email',
            'email'
        );
    }

    public function blogs()
    {
        return $this->hasMany(Blog::class, 'author_id');
    }

    public function orders()
    {
        return $this->hasMany(Order::class, 'user_id');
    }

    public function entitlements()
    {
        return $this->hasMany(UserEntitlement::class, 'user_id');
    }

    public function academicCategory()
    {
        return $this->belongsTo(AcademicCategory::class, 'academic_category_id');
    }

    public function admissions()
    {
        return $this->hasMany(Admission::class, 'user_id');
    }

    public function latestAdmission()
    {
        return $this->hasOne(Admission::class, 'user_id')->latestOfMany();
    }
}

