<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\BelongsToTenant;

class Images extends Model
{
    protected $connection = 'tenant';
    protected $table = 'images';

    protected $fillable = [
        'filename',
        'filepath',
    ];

    public function getUrlAttribute()
    {
        return asset($this->path);
    }
}
