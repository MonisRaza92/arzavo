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

    // Force deep object decoding for all access
    public function getColorsAttribute($value)
    {
        return json_decode($value);  // full object of all scheme groups
    }

    // Enable direct access like $scheme->scheme_colors, $scheme->primary_btn
    public function __get($key)
    {
        // Decode raw original JSON (without recursion)
        $json = json_decode($this->attributes['colors'] ?? '{}');

        if (is_object($json) && property_exists($json, $key)) {
            return $json->$key; // return the object for that group
        }

        return parent::__get($key);
    }

    public function sections()
    {
        return $this->hasMany(Section::class, 'color_scheme_id');
    }
}
