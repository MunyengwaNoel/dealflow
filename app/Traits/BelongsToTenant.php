<?php

namespace App\Traits;

use App\Models\Tenant;
use App\Tenancy\TenantScope;
use Illuminate\Database\Eloquent\Builder;

trait BelongsToTenant
{
    public static function bootBelongsToTenant(): void
    {
        static::addGlobalScope(new TenantScope());

        static::creating(function ($model): void {
            if (empty($model->tenant_id) && app()->bound('tenant') && app('tenant')) {
                $model->tenant_id = app('tenant')->id;
            }
        });
    }

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function scopeForTenant(Builder $query, int $tenantId): Builder
    {
        return $query->withoutGlobalScope(TenantScope::class)->where('tenant_id', $tenantId);
    }
}
