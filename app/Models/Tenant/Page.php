<?php
namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    protected $connection = 'tenant';
    protected $fillable = [
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
