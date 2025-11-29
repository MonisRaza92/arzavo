<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;

class Subjects extends Model
{
    protected $connection = 'tenant';
    protected $table = 'subjects';

    protected $fillable = [
        'image',
        'name',
    ];

    public function courses()
    {
        return $this->hasMany(Courses::class);
    }
}
