<?php

namespace App\Models\Arzavo;

use Illuminate\Database\Eloquent\Model;

class Usage extends Model
{
    protected $fillable = [
        'tenant_id',
        'key',
        'used_value',
    ];

    // 🔹 Relations
    public function tenant()
    {
        return $this->belongsTo(\App\Models\Arzavo\Tenant::class);
    }
}