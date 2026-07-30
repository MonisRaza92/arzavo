<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReviewMedia extends Model
{
    protected $connection = 'tenant';

    protected $fillable = [
        'review_id',
        'file_path',
        'file_type',
    ];

    public function review(): BelongsTo
    {
        return $this->belongsTo(Review::class);
    }
}
