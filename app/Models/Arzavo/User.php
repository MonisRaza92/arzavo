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
        'name',
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

    protected static function booted()
    {
        static::creating(function ($user) {
            if (empty($user->fname) && empty($user->lname)) {
                $parts = explode(' ', trim($user->name ?? 'User'), 2);
                $user->fname = $parts[0] ?: 'User';
                $user->lname = $parts[1] ?? '';
            }
            if (empty($user->username)) {
                $base = strtolower(preg_replace('/[^a-zA-Z0-9]/', '', ($user->fname . $user->lname) ?: 'user'));
                $user->username = ($base ?: 'user') . '_' . rand(1000, 9999);
            }
        });
    }

    public function setNameAttribute($value)
    {
        $parts = explode(' ', trim($value ?? ''), 2);
        $this->attributes['fname'] = $parts[0] ?: 'User';
        $this->attributes['lname'] = $parts[1] ?? '';
    }

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
