<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StaffPayroll extends Model
{
    use HasFactory;

    protected $connection = 'tenant';
    protected $table = 'staff_payrolls';

    protected $fillable = [
        'user_id',
        'month',
        'year',
        'base_salary',
        'total_days',
        'present_days',
        'deductions',
        'bonuses',
        'reimbursements',
        'net_salary',
        'breakdown',
        'status',
        'paid_at',
        'payment_method',
        'transaction_reference',
        'notes',
        'processed_by',
    ];

    protected $casts = [
        'base_salary' => 'decimal:2',
        'deductions' => 'decimal:2',
        'bonuses' => 'decimal:2',
        'reimbursements' => 'decimal:2',
        'net_salary' => 'decimal:2',
        'breakdown' => 'array',
        'present_days' => 'float',
        'paid_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function processedByUser()
    {
        return $this->belongsTo(User::class, 'processed_by');
    }
}
