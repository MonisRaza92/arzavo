<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CourseModule extends Model
{
    use HasFactory;
    protected $connection = 'tenant';

    protected $fillable = [
        'course_id',
        'title',
        'description',
        'is_active',
        'order',
    ];

    /* -----------------------------
     | RELATIONS
     |-----------------------------*/

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    // STEP J me use hoga
    public function lessons()
    {
        return $this->hasMany(CourseLesson::class)
            ->orderBy('order');
    }

    /* -----------------------------
     | SCOPES
     |-----------------------------*/

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
