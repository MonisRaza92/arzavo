<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\BelongsToTenant;

class Settings extends Model
{
    protected $connection = 'tenant';
    protected $fillable = ['key', 'value'];

    // Setting save/update
    public static function set($key, $value)
    {
        return static::updateOrCreate(
            ['key' => $key],
            ['value' => $value]
        );
    }

    // Setting get
    public static function get($key, $default = null)
    {
        $setting = static::where('key', $key)->first();
        return $setting ? $setting->value : $default;
    }
}
