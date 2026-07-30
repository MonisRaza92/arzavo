<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transaction extends Model
{
    protected $connection = 'tenant';

    protected $fillable = [
        'order_id',
        'gateway',
        'transaction_id',
        'reference_number',
        'type',
        'amount',
        'currency',
        'status',
        'proof_file_path',
        'gateway_payload',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'gateway_payload' => 'array',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }
}
