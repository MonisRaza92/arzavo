<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Courses;
use App\Models\Traits\BelongsToTenant;

class Categories extends Model
{
    use BelongsToTenant;
    protected $table = 'categories';

    protected $fillable = [
        'tenant_id',
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
