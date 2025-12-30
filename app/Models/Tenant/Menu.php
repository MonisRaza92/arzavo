<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Menu extends Model
{
    protected $connection = 'tenant';
    protected $fillable = [
        'name',
        'slug',
        'location',
    ];

    /**
     * Menu ke saare items (top-level)
     */
    public function items()
    {
        return $this->hasMany(MenuItem::class)
            ->whereNull('parent_id')
            ->orderBy('order');
    }

    /**
     * Saare items (including children)
     */
    public function allItems()
    {
        return $this->hasMany(MenuItem::class)
            ->orderBy('order');
    }
}
