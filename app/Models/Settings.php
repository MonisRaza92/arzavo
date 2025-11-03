<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\BelongsToTenant;

class Settings extends Model
{
    use BelongsToTenant;
    protected $fillable = ['tenant_id', 'key', 'value'];

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
    public function tenant()
    {
        return $this->belongsTo(Tenant::class, 'tenant_id');
    }
}
