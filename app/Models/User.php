<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Courses;
use App\Models\Contents;
use App\Models\FeePlans;
use App\Models\FeePayments;
use App\Models\Subjects;
use App\Models\Classes;
use App\Models\Traits\BelongsToTenant;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, BelongsToTenant;

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
        'number',
        'email',
        'dob',
        'class_id',
        'subject_id',
        'address',
        'city',
        'state',
        'country',
        'pincode',
        'about',
        'password',
        'role',
        'status',
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
            'password' => 'hashed',
        ];
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

    public function tenants()
    {
        return $this->hasMany(Tenant::class, 'user_id');
    }

    public function courses()
    {
        return $this->hasMany(Courses::class, 'user_id');
    }

    public function contents()
    {
        return $this->hasMany(Contents::class, 'user_id');
    }
    public function feePlans()
    {
        return $this->hasMany(FeePlans::class, 'student_id');
    }
    public function feePayments()
    {
        return $this->hasMany(FeePayments::class, 'student_id');
    }
    public function subject()
    {
        return $this->belongsTo(Subjects::class, 'subject_id');
    }

    public function class()
    { // "class" reserved word hai
        return $this->belongsTo(Classes::class, 'class_id');
    }
}
