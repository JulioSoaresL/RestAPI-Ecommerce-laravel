<?php

namespace App\Traits;

use App\Models\Tenant;
use Illuminate\Database\Eloquent\Builder;

trait BelongsToTenant
{
    protected static function bootBelongsToTenant(): void
    {
        static::addGlobalScope('tenant_id', function (Builder $builder) {
            if (app()->bound('currentTenant')) {
                $builder->where('tenant_id', app('currentTenant')->id);
            }
        });

        static::creating(function ($model) {
            if (app()->bound('currentTenant') && ! $model->tenant_id) {
                $model->tenant_id = app('currentTenant')->id;
            }
        });
    }

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }
}
