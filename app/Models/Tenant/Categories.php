<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\BelongsToTenant;

class Categories extends Model
{
    protected $connection = 'tenant';
    protected $table = 'categories';

    protected $fillable = [
        'image',
        'name',
        'description',
    ];

    // Relationship with Courses
    public function courses()
    {
        return $this->hasMany(Courses::class, 'category_id');
    }
}
