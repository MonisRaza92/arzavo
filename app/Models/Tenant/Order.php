<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Tenant\User;

class Order extends Model
{
    use SoftDeletes;

    protected $connection = 'tenant';

    protected $fillable = [
        'order_number',
        'user_id',
        'customer_name',
        'customer_email',
        'customer_phone',
        'currency',
        'subtotal',
        'discount_amount',
        'tax_amount',
        'shipping_amount',
        'grand_total',
        'payment_status',
        'fulfillment_status',
        'payment_gateway',
        'shipping_address',
        'billing_address',
        'notes',
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'shipping_amount' => 'decimal:2',
        'grand_total' => 'decimal:2',
        'shipping_address' => 'array',
        'billing_address' => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }

    public function latestTransaction()
    {
        return $this->hasOne(Transaction::class)->latestOfMany();
    }

    /**
     * Return formatted payment status badge HTML.
     */
    public function getPaymentStatusBadgeAttribute(): string
    {
        return match ($this->payment_status) {
            'paid'                  => '<span class="px-2 py-1 text-xs font-bold bg-green-100 text-green-800 rounded">Paid</span>',
            'verification_pending'  => '<span class="px-2 py-1 text-xs font-bold bg-yellow-100 text-yellow-800 rounded">Awaiting Verification</span>',
            'failed'                => '<span class="px-2 py-1 text-xs font-bold bg-red-100 text-red-800 rounded">Failed</span>',
            'refunded'              => '<span class="px-2 py-1 text-xs font-bold bg-gray-100 text-gray-800 rounded">Refunded</span>',
            default                 => '<span class="px-2 py-1 text-xs font-bold bg-blue-100 text-blue-800 rounded">Unpaid</span>',
        };
    }
}
