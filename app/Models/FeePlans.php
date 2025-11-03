<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\BelongsToTenant;

class FeePlans extends Model
{
    use BelongsToTenant;
    protected $table = 'fee_plans';

    protected $fillable = [
        'tenant_id',
        'student_id',
        'plan_type',
        'amount',
        'start_date',
        'due_day',
        'end_date',
    ];

    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }
    public function feePayments()
    {
        return $this->hasMany(FeePayments::class, 'fee_plan_id');
    }
}
