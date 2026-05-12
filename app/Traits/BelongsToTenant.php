<?php

namespace App\Traits;

use App\Models\Tenant;
use App\Tenancy\TenantScope;
use Illuminate\Database\Eloquent\Builder;

trait BelongsToTenant
{
    public static function bootBelongsToTenant(): void
    {
        static::addGlobalScope(new TenantScope);

        static::creating(function ($model): void {
            if (! empty($model->tenant_id)) {
                return;
            }

            // Full HTTP requests: middleware binds the tenant on the container.
            if (app()->bound('tenant') && app('tenant')) {
                $model->tenant_id = app('tenant')->id;

                return;
            }

            // Livewire / queued jobs: `tenant` is often not rebound; use the signed-in user.
            $user = auth()->user();
            if ($user && $user->tenant_id) {
                $model->tenant_id = $user->tenant_id;
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
