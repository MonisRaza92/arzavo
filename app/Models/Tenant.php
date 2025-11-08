<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tenant extends Model
{
    protected $fillable = [
        'user_id',
        'name',
        'subdomain',
        'custom_domain',
        'domain_verified',
        'domain_verified_at',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'domain_verified' => 'boolean',
    ];
    public function getDomainAttribute()
    {
        if ($this->domain_verified && !empty($this->custom_domain)) {
            return $this->custom_domain;
        }
        return null;
    }
    // Relation: Tenant belongs to User (Owner)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relation: Tenant has many Settings
    public function settings()
    {
        return $this->hasMany(Settings::class);
    }

    public function customizes()
    {
        return $this->hasMany(Customizes::class);
    }

    public function courses()
    {
        return $this->hasMany(Courses::class);
    }
}
