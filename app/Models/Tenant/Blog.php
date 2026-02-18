<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Blog extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'title',
        'slug',
        'heading',
        'content',
        'featured_image',
        'image_alt',
        'author_id',
        'status',
        'published_at',
        'meta_title',
        'meta_description',
    ];

    protected $casts = [
        'published_at' => 'datetime',
    ];

    /*
    |--------------------------------------------------------------------------
    | Boot - auto slug generate
    |--------------------------------------------------------------------------
    */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($blog) {

            if (empty($blog->slug)) {

                $slug = Str::slug($blog->title);
                $count = Blog::where('slug', 'LIKE', "{$slug}%")->count();

                $blog->slug = $count ? "{$slug}-".($count+1) : $slug;
            }

        });
    }

    /*
    |--------------------------------------------------------------------------
    | Scope - only published blogs
    |--------------------------------------------------------------------------
    */
    public function scopePublished($query)
    {
        return $query->where('status','published')
                     ->where(function($q){
                         $q->whereNull('published_at')
                           ->orWhere('published_at','<=',now());
                     });
    }

    /*
    |--------------------------------------------------------------------------
    | Author relation (optional)
    |--------------------------------------------------------------------------
    */
    public function author()
    {
        return $this->belongsTo(User::class,'author_id');
    }

    /*
    |--------------------------------------------------------------------------
    | Featured Image URL accessor
    |--------------------------------------------------------------------------
    */
    public function getFeaturedImageUrlAttribute()
    {
        if(!$this->featured_image){
            return asset('images/default-blog.png');
        }

        if(Str::startsWith($this->featured_image,['http://','https://'])){
            return $this->featured_image;
        }

        return asset('storage/'.$this->featured_image);
    }

    /*
    |--------------------------------------------------------------------------
    | Reading time estimate (simple)
    |--------------------------------------------------------------------------
    */
    public function getReadingTimeAttribute()
    {
        $words = str_word_count(strip_tags($this->content));
        return max(1, ceil($words / 200)); // 200 wpm avg
    }
}