<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BookCategory extends Model
{
    protected $connection = 'tenant';

    protected $fillable = [
        'name',
        'slug',
        'image',
        'description',
        'status',
        'order',
    ];

    protected $casts = [
        'status' => 'boolean',
        'order'  => 'integer',
    ];

    /**
     * Get all books under this category.
     */
    public function books(): HasMany
    {
        return $this->hasMany(Book::class, 'book_category_id');
    }
}
