<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use App\Contracts\Commerce\PurchasableContract;
use App\Traits\HasVariants;
use App\Traits\HasReviews;
use App\Models\Tenant\User;

class Course extends Model implements PurchasableContract
{
    use HasFactory, HasVariants, HasReviews;

    protected $connection = 'tenant';

    protected $fillable = [
        'title',
        'slug',
        'description',
        'thumbnail',
        'video',
        'language',
        'level',
        'duration',
        'max_students',
        'is_paid',
        'price',
        'discount_price',
        'is_public',
        'requires_enrollment',
        'enable_modules',
        'enable_lessons',
        'enable_quizzes',
        'enable_assignments',
        'enable_certificates',
        'enable_reviews',
        'status',
        'publish_date',
        'expire_date',
        'total_enrollments',
        'total_reviews',
        'user_id',
    ];

    public function getPurchasableTitle(): string
    {
        return $this->title;
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }

    /* ---------------------------------
     | RELATIONS
     |---------------------------------*/

    // Course belongs to creator / teacher
    public function author()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // MANY classes
    public function classes()
    {
        return $this->belongsToMany(
            ClassCourse::class,
            'course_class_course'
        );
    }

    protected function className(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->classes->first()?->name,
        );
    }
    protected function classId(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->classes->first()?->id,
        );
    }

    // MANY subjects
    public function subjects()
    {
        return $this->belongsToMany(
            Subject::class,
            'course_subject'
        );
    }

    // Modules
    public function modules()
    {
        return $this->hasMany(CourseModule::class)
            ->orderBy('order');
    }

    // Direct lessons (agar modules disabled ho)
    public function lessons()
    {
        return $this->hasMany(CourseLesson::class)
            ->whereNull('course_module_id')
            ->orderBy('order');
    }

    /* ---------------------------------
     | SCOPES
     |---------------------------------*/

    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    public function scopePublic($query)
    {
        return $query->where('is_public', true);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }
    public function usesModules()
    {
        return (bool) $this->enable_modules;
    }
    // All lessons
    public function allLessons()
    {
        return $this->hasMany(CourseLesson::class)
            ->orderBy('order');
    }

    // Direct lessons (modules OFF)
    public function directLessons()
    {
        return $this->hasMany(CourseLesson::class)
            ->whereNull('course_module_id')
            ->orderBy('order');
    }
    public function teacher()
    {
        return $this->hasOne(User::class, 'user_id');
    }
    public function getItemsAttribute()
    {
        $items = collect();

        // Modules
        foreach ($this->modules as $module) {
            $items->push([
                'type'  => 'module',
                'order' => $module->order,
                'model' => $module,
            ]);
        }

        // Direct lessons (module_id = null)
        foreach ($this->directLessons as $lesson) {
            $items->push([
                'type'  => 'lesson',
                'order' => $lesson->order,
                'model' => $lesson,
            ]);
        }

        return $items->sortBy('order')->values();
    }
}
