<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use App\Models\Tenant\User;

class UserEntitlement extends Model
{
    protected $connection = 'tenant';

    protected $fillable = [
        'user_id',
        'order_id',
        'entitable_type',
        'entitable_id',
        'variant_id',
        'can_download',
        'can_stream_online',
        'download_limit',
        'downloads_count',
        'expires_at',
    ];

    protected $casts = [
        'can_download' => 'boolean',
        'can_stream_online' => 'boolean',
        'download_limit' => 'integer',
        'downloads_count' => 'integer',
        'expires_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function entitable(): MorphTo
    {
        return $this->morphTo();
    }

    public function variant(): BelongsTo
    {
        return $this->belongsTo(ProductVariant::class, 'variant_id');
    }
}
