<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use App\Models\Traits\BelongsToTenant;

class Content extends Model
{
    protected $connection = 'tenant';
    protected $fillable = [
        'type',
        'filename',
        'filepath',
        'is_active',
        'user_id'
    ];
    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function teacher()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
