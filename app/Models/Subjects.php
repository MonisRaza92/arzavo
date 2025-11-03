<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Courses;
use App\Models\Traits\BelongsToTenant;

class Subjects extends Model
{
    use BelongsToTenant;
    protected $table = 'subjects';

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
