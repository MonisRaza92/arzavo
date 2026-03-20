<?php

namespace App\Models\Arzavo;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $connection = 'mysql';
    protected $fillable = [
        'tenant_id',
        'invoice_id',
        'order_id',
        'payment_id',
        'amount',
        'status',
    ];

    public function invoice()
    {
        return $this->belongsTo(\App\Models\Arzavo\Invoice::class);
    }
}