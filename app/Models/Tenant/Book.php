<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Book extends Model
{
    protected $connection = 'tenant';

    protected $fillable = [
        'title',
        'slug',
        'author',
        'publisher',
        'edition',
        'isbn',
        'description',
        'pages_count',
        'cover_image',
        'file_path',
        'preview_file_path',
        'price_type',
        'price',
        'sale_price',
        'access_type',
        'is_active',
        'is_featured',
        'views_count',
        'downloads_count',
        'book_category_id',
        'academic_category_id',
        'class_course_id',
        'subject_id',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'sale_price' => 'decimal:2',
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
        'pages_count' => 'integer',
        'views_count' => 'integer',
        'downloads_count' => 'integer',
    ];

    /**
     * Get the category this book belongs to.
     */
    public function bookCategory(): BelongsTo
    {
        return $this->belongsTo(BookCategory::class, 'book_category_id');
    }

    /**
     * Get the academic category this book belongs to.
     */
    public function academicCategory(): BelongsTo
    {
        return $this->belongsTo(AcademicCategory::class, 'academic_category_id');
    }

    /**
     * Get the class course this book belongs to.
     */
    public function classCourse(): BelongsTo
    {
        return $this->belongsTo(ClassCourse::class, 'class_course_id');
    }

    /**
     * Get the subject this book belongs to.
     */
    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class, 'subject_id');
    }
}
