<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Section extends Model
{
    protected $fillable = ['page_id', 'name', 'type', 'settings', 'order', 'is_active'];

    protected $casts = [
        'settings' => 'array',
        'is_active' => 'boolean'
    ];

    public function page()
    {
        return $this->belongsTo(Page::class);
    }
}
