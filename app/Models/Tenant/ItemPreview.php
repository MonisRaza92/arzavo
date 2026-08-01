<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class ItemPreview extends Model
{
    protected $connection = 'tenant';

    protected $fillable = [
        'previewable_type',
        'previewable_id',
        'file_path',
        'title',
        'sort_order',
    ];

    protected $casts = [
        'sort_order' => 'integer',
    ];

    public function previewable(): MorphTo
    {
        return $this->morphTo();
    }
}
