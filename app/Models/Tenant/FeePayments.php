<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\BelongsToTenant;

class FeePayments extends Model
{
    protected $connection = 'tenant';
    protected $table = 'fee_payments';

    protected $fillable = [
        'student_id',
        'fee_plan_id',
        'month_year',
        'due_date',
        'amount',
        'arrears',
        'discount',
        'fine',
        'final_amount',
        'amount_paid',
        'payment_date',
        'payment_method',
        'payment_type',
        'transaction_id',
        'notes',
        'status',
    ];

    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }
    public function feePlan()
    {
        return $this->belongsTo(FeePlans::class, 'fee_plan_id');
    }
    public function calculateFinalAmount()
    {
        $this->final_amount = $this->amount + $this->arrears - $this->discount + $this->fine;
        return $this->final_amount;
    }
}
