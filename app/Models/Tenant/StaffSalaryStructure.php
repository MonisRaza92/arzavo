<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StaffSalaryStructure extends Model
{
    use HasFactory;

    protected $connection = 'tenant';
    protected $table = 'staff_salary_structures';

    protected $fillable = [
        'user_id',
        'base_salary',
        'salary_type',
        'fixed_deductions',
        'fixed_bonuses',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'base_salary' => 'decimal:2',
            'fixed_deductions' => 'array',
            'fixed_bonuses' => 'array',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
