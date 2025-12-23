<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use App\Models\Traits\BelongsToTenant;

class Contents extends Model
{
    protected $connection = 'tenant';
    protected $fillable = [
        'type',
        'file',
        'title',
        'slug',
        'subject_id',
        'class_id',
        'description',
        'price',
        'discount_price',
        'is_free',
        'duration',
        'thumbnail',
        'status',
        'user_id'
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($content) {
            if (empty($content->slug)) {
                $slug = Str::slug($content->title);
                $count = static::where('slug', 'LIKE', "{$slug}%")->count();
                $content->slug = $count ? "{$slug}-" . ($count + 1) : $slug;
            }
        });
    }


    public function subject()
    {
        return $this->belongsTo(Subjects::class, 'subject_id');
    }

    public function class()
    {
        return $this->belongsTo(Classes::class, 'class_id');
    }

    public function teacher()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function courses()
    {
        return $this->belongsToMany(Courses::class, 'course_contents', 'content_id', 'course_id')
            ->withPivot('order', 'is_required', 'is_locked')
            ->withTimestamps();
    }
}
