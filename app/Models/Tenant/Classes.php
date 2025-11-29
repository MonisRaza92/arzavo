<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;

class Classes extends Model
{
    protected $connection = 'tenant';
    protected $table = 'classes';

    protected $fillable = [
        'image',
        'name',
    ];

    public function courses()
    {
        return $this->hasMany(Courses::class);
    }
}
