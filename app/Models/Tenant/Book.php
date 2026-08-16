<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Contracts\Commerce\PurchasableContract;
use App\Traits\HasVariants;
use App\Traits\HasReviews;

class Book extends Model implements PurchasableContract
{
    use HasVariants, HasReviews;

    protected $connection = 'tenant';

    protected $fillable = [
        'title',
        'slug',
        'author',
        'publisher',
        'edition',
        'isbn',
        'short_description',
        'description',
        'highlights',
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
        'meta_title',
        'meta_description',
        'meta_keywords',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'sale_price' => 'decimal:2',
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
        'pages_count' => 'integer',
        'views_count' => 'integer',
        'downloads_count' => 'integer',
        'highlights' => 'array',
    ];

    public function previews()
    {
        return $this->morphMany(ItemPreview::class, 'previewable')->orderBy('sort_order', 'asc')->orderBy('id', 'asc');
    }

    public function getPurchasableTitle(): string
    {
        return $this->title;
    }

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
