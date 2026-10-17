<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;

class FeeStructure extends Model
{
    protected $connection = 'tenant';
    protected $table = 'fee_structures';

    protected $fillable = [
        'title',
        'scope',
        'academic_category_id',
        'class_id',
        'subject_id',
        'batch_id',
        'plan_type',
        'amount',
        'due_day',
        'start_date',
        'is_active',
        'description',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'due_day' => 'integer',
        'is_active' => 'boolean',
        'start_date' => 'date',
    ];

    public function category()
    {
        return $this->belongsTo(AcademicCategory::class, 'academic_category_id');
    }

    public function classCourse()
    {
        return $this->belongsTo(ClassCourse::class, 'class_id');
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class, 'subject_id');
    }

    public function batch()
    {
        return $this->belongsTo(Batch::class, 'batch_id');
    }

    public function feePlans()
    {
        return $this->hasMany(FeePlans::class, 'fee_structure_id');
    }

    /**
     * Get target human-readable label
     */
    public function getTargetNameAttribute(): string
    {
        return match ($this->scope) {
            'batch' => $this->batch ? $this->batch->name : 'All Batches',
            'subject' => $this->subject ? $this->subject->name : 'All Subjects',
            'class' => $this->classCourse ? $this->classCourse->name : 'All Classes',
            'category' => $this->category ? $this->category->name : 'All Categories',
            default => 'General Tier',
        };
    }
}
