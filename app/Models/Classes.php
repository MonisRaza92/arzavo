<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Courses;
use App\Models\Traits\BelongsToTenant;

class Classes extends Model
{
    use BelongsToTenant;
    protected $table = 'classes';

    protected $fillable = [
        'tenant_id',
        'image',
        'name',
    ];

    public function courses()
    {
        return $this->hasMany(Courses::class);
    }
}
