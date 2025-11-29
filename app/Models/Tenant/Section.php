<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;

class Section extends Model
{
    protected $connection = 'tenant';
    protected $fillable = ['page_id', 'name', 'type', 'icon', 'settings', 'order', 'is_active', 'color_scheme_id'];

    protected $casts = [
        'settings' => 'array',
        'is_active' => 'boolean'
    ];

    public function page()
    {
        return $this->belongsTo(Page::class);
    }
    public function colorScheme()
    {
        return $this->belongsTo(ColorScheme::class, 'color_scheme_id');
    }
    public function blocks()
    {
        return $this->hasMany(Block::class)
            ->whereNull('parent_block_id') // only top-level blocks
            ->orderBy('order');
    }
}
