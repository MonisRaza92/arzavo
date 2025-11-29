<?php

namespace App\Models\Arzavo;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlanFeature extends Model
{
    use HasFactory;

    protected $fillable = [
        'plan_id',
        'key',
        'value',
    ];

    /**
     * Each feature belongs to one plan
     */
    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }
}
