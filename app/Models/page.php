<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\BelongsToTenant;

class page extends Model
{
    use BelongsToTenant;
    protected $fillable = [
        'tenant_id',
        'name',
        'slug',
        'meta_title',
        'meta_description',
        'status',
    ];

    public function sections()
    {
        return $this->hasMany(Section::class)->orderBy('order');
    }
}
