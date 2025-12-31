<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    protected $connection = 'tenant';
    protected $fillable = [
        'name',
        'slug',
        'is_system_page',
        'meta_title',
        'meta_description',
        'is_active',
    ];
    protected $casts = [
        'is_system_page'=> 'boolean',
        'is_active' => 'boolean',
    ];

    public function sections()
    {
        return $this->hasMany(Section::class)->orderBy('order');
    }
}
