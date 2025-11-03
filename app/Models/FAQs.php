<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\BelongsToTenant;

class FAQs extends Model
{
    use BelongsToTenant;
    protected $table = 'faqs';

    protected $fillable = [
        'tenant_id',
        'question',
        'answer'
    ];
}
