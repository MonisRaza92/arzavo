<?php

namespace App\Models\Arzavo;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tenant extends Model
{
    use HasFactory;

    protected $fillable = [
        'admin_id',
        'name',
        'logo',
        'banner',
        'heading',
        'about',
        'subdomain',
        'custom_domain',
        'domain_verified',
        'domain_verified_at',
        'phone',
        'email',
        'address',
        'city',
        'state',
        'country',
        'pincode',
        'status',
        'db_name',
        'db_username',
        'db_password',
        'social_links',
    ];

    protected $casts = [
        'social_links' => 'array',
        'domain_verified_at' => 'datetime',
        'domain_verified' => 'boolean',
    ];

    /**
     * Tenant Admin User Relation
     * Main Platform User who created/owns the tenant
     */
    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    /**
     * Subscription (Current active plan)
     */
    public function subscription()
    {
        return $this->hasOne(Subscription::class);
    }

    /**
     * Access Plan Directly
     */
    public function plan()
    {
        return $this->hasOneThrough(
            Plan::class,
            Subscription::class
        );
    }

    /**
     * Auto-generate URL based on subdomain or custom domain
     */
    public function getUrlAttribute()
    {
        // Custom Domain (school.com)
        if ($this->custom_domain && $this->domain_verified) {
            return "https://{$this->custom_domain}";
        }

        // Subdomain (tenant.arzavo.in)
        if ($this->subdomain) {
            return "https://{$this->subdomain}." . config('app.domain');
        }

        return null;
    }
    public function users()
    {
        return $this->hasMany(\App\Models\Tenant\User::class);
    }
    public function students()
    {
        return $this->users()->where('role', 'student');
    }

    public function usages()
    {
        return $this->hasMany(\App\Models\Arzavo\Usage::class);
    }

    public function invoices()
    {
        return $this->hasMany(\App\Models\Arzavo\Invoice::class);
    }
}