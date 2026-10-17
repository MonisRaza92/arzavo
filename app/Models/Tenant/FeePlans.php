<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\BelongsToTenant;

class FeePlans extends Model
{
    protected $connection = 'tenant';
    protected $table = 'fee_plans';

    protected $fillable = [
        'student_id',
        'fee_structure_id',
        'plan_type',
        'amount',
        'is_override',
        'override_reason',
        'start_date',
        'due_day',
        'end_date',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'is_override' => 'boolean',
        'due_day' => 'integer',
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function feeStructure()
    {
        return $this->belongsTo(FeeStructure::class, 'fee_structure_id');
    }

    public function feePayments()
    {
        return $this->hasMany(FeePayments::class, 'fee_plan_id');
    }
}
