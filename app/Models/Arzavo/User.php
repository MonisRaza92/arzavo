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
        return $this->fname . ' ' . $this->lname;
    }
    public function socialAccounts()
    {
        return $this->hasMany(SocialAccount::class);
    }
}
