<?php

namespace App\Models\Traits;

use Illuminate\Database\Eloquent\Builder;

trait BelongsToTenant
{
    protected static function bootBelongsToTenant()
    {
        static::addGlobalScope('tenant', function (Builder $builder) {
            if (app()->has('currentTenant')) {
                $builder->where('tenant_id', app('currentTenant')->id);
            }
        });

        static::creating(function ($model) {
            if (app()->has('currentTenant')) {
                $model->tenant_id = app('currentTenant')->id;
            }
        });
    }
}
