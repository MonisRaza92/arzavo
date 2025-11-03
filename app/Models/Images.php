<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\BelongsToTenant;

class Images extends Model
{
    use BelongsToTenant;

    protected $table = 'images';

    protected $fillable = [
        'tenant_id',
        'filename',
        'filepath',
    ];

    public function getUrlAttribute()
    {
        return asset($this->path);
    }
}
