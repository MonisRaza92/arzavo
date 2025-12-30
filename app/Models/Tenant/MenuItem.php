<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MenuItem extends Model
{
    protected $connection = 'tenant';
    protected $fillable = [
        'menu_id',
        'parent_id',
        'name',
        'link',
        'target',
        'order',
    ];

    /**
     * Parent menu
     */
    public function menu()
    {
        return $this->belongsTo(Menu::class);
    }

    /**
     * Parent item (for nesting)
     */
    public function parent()
    {
        return $this->belongsTo(MenuItem::class, 'parent_id');
    }

    /**
     * Child items (recursive)
     */
    public function children()
    {
        return $this->hasMany(MenuItem::class, 'parent_id')
            ->orderBy('order');
    }

    /**
     * Recursive children (important for rendering)
     */
    public function childrenRecursive()
    {
        return $this->children()->with('childrenRecursive');
    }
}
