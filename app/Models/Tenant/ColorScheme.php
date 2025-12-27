<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;

class ColorScheme extends Model
{
    protected $connection = 'tenant';

    protected $fillable = ['colors'];

    protected $casts = [
        'colors' => 'array',
    ];

    // Enable direct access like $scheme->scheme_colors, $scheme->primary_btn
    public function __get($key)
    {
        $colors = $this->getAttribute('colors'); // get raw attribute value

        if (is_array($colors) && isset($colors[0]) && is_array($colors[0])) {
            if (array_key_exists($key, $colors[0])) {
                return (object) $colors[0][$key]; // 👈 IMPORTANT
            }
        }

        return parent::__get($key);
    }


    public function sections()
    {
        return $this->hasMany(Section::class, 'color_scheme_id');
    }

    public function blocks()
    {
        return $this->hasMany(Block::class, 'color_scheme_id');
    }
}
