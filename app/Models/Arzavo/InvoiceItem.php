<?php

namespace App\Models\Arzavo;

use Illuminate\Database\Eloquent\Model;

class InvoiceItem extends Model
{
    protected $connection = 'mysql';
    protected $fillable = [
        'invoice_id',
        'type',
        'description',
        'amount',
    ];

    public function invoice()
    {
        return $this->belongsTo(\App\Models\Arzavo\Invoice::class);
    }
}