<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AcademicCategory extends Model
{
    use HasFactory;
    protected $connection = 'tenant';

    protected $fillable = [
        'name',
        'slug',
        'description',
        'image',
        'order',
        'status',
    ];

    protected $casts = [
        'status' => 'boolean',
    ];

    /**
     * Category has many Classes/Courses
     */
    public function classCourses()
    {
        return $this->hasMany(ClassCourse::class, 'academic_category_id')->orderBy('order');
    }

    public function scopeActive($query)
    {
        return $query->where('status', true);
    }
}
