<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\BelongsToTenant;

class FAQs extends Model
{
    protected $connection = 'tenant';
    protected $table = 'faqs';

    protected $fillable = [
        'question',
        'answer'
    ];
}
