<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Categories;
use App\Models\Subjects;
use App\Models\Classes;
use Illuminate\Support\Str;
use App\Models\Traits\BelongsToTenant;

class Courses extends Model
{
    use HasFactory, BelongsToTenant;

    protected $table = 'courses';

    protected $fillable = [
        'tenant_id',
        'title',
        'slug',
        'description',
        'video',
        'thumbnail',
        'price',
        'discount_price',
        'max_students',
        'is_featured',
        'is_popular',
        'is_new',
        'is_recommended',
        'is_certified',
        'allow_reviews',
        'total_reviews',
        'total_enrollments',
        'user_id',
        'language',
        'duration',
        'level',
        'status',
        'category_id',
        'subject_id',
        'class_id',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($course) {
            if (empty($course->slug)) {
                $slug = Str::slug($course->title);
                $count = static::where('slug', 'LIKE', "{$slug}%")->count();
                $course->slug = $count ? "{$slug}-" . ($count + 1) : $slug;
            }
        });
    }

    // Relation with User (Teacher/Author)
    public function teacher()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function category()
    {
        return $this->belongsTo(Categories::class);
    }

    public function subject()
    {
        return $this->belongsTo(Subjects::class, 'subject_id');
    }

    public function class()
    { // "class" reserved word hai
        return $this->belongsTo(Classes::class, 'class_id');
    }

    public function contents()
    {
        return $this->belongsToMany(Contents::class, 'course_contents')
            ->withPivot(['order', 'is_required', 'is_locked'])
            ->withTimestamps()
            ->orderBy('order');
    }
}
