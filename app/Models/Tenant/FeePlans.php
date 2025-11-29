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
