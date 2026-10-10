<?php

namespace App\Models\Arzavo;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class User extends Authenticatable
{
    protected $connection = 'mysql';

    use HasFactory, Notifiable;

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
        'arzaq_user_id',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'last_login' => 'datetime',
    ];

    // Tenant relationship (global user → created tenants)
    public function tenants()
    {
        return $this->hasMany(Tenant::class, 'admin_id');
    }

    // Name accessor
    public function getFullNameAttribute()
    {
        return trim(($this->fname ?? '') . ' ' . ($this->lname ?? ''));
    }

    public function getNameAttribute()
    {
        return $this->getFullNameAttribute() ?: ($this->username ?? 'User');
    }

    public function getAvatarAttribute()
    {
        return $this->profile_picture;
    }
    public function socialAccounts()
    {
        return $this->hasMany(SocialAccount::class);
    }
}
