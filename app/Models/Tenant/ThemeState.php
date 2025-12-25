<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;

class ThemeState extends Model
{
    protected $connection = 'tenant';
    protected $table = 'theme_states';

    // TENANT DB (auto switch hoga)
    protected $fillable = [
        'theme_id',
        'theme_name',
        'theme_slug',
        'theme_version',
        'applied_with_reset',
        'applied_at',
        'meta',
    ];

    protected $casts = [
        'applied_with_reset' => 'boolean',
        'applied_at' => 'datetime',
        'meta' => 'array',
    ];

    /* -------------------------
     | Helpers
     |--------------------------*/

    public static function current(): ?self
    {
        return self::latest('applied_at')->first();
    }

    public static function set(array $data): self
    {
        // Ensure only one active theme
        self::query()->delete();

        return self::create(array_merge(
            $data,
            ['applied_at' => now()]
        ));
    }
}
