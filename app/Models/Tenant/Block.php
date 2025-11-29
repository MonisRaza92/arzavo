<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;

class Block extends Model
{
    protected $connection = 'tenant';
    protected $fillable = ['section_id', 'parent_block_id', 'name', 'type', 'icon', 'settings', 'order', 'is_active'];

    protected $casts = [
        'settings' => 'array',
        'is_active' => 'boolean'
    ];

    public function section()
    {
        return $this->belongsTo(Section::class);
    }

    // Block belongs to another block (parent)
    public function parent()
    {
        return $this->belongsTo(Block::class, 'parent_block_id');
    }

    // Block has many children blocks (nested blocks)
    public function children()
    {
        return $this->hasMany(Block::class, 'parent_block_id')
            ->orderBy('order');
    }
}
