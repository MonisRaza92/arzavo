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
        return $this->hasOne(TenantSubscription::class, 'tenant_id');
    }

    /**
     * Access Plan Directly
     */
    public function plan()
    {
        return $this->subscription?->plan();
    }

    /**
     * Auto-generate URL based on subdomain or custom domain
     */
    public function getUrlAttribute()
    {
        if ($this->custom_domain) {
            return "https://{$this->custom_domain}";
        }

        if ($this->subdomain) {
            return "https://{$this->subdomain}.arzavo.com";
        }

        return null;
    }
}
