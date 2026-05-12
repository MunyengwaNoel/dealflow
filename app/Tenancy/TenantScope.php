<?php

namespace App\Tenancy;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Support\Facades\Auth;

class TenantScope implements Scope
{
    public function apply(Builder $builder, Model $model): void
    {
        $tenantId = $this->resolveTenantId();

        if ($tenantId !== null && $model->getTable() !== 'tenants') {
            $builder->where($model->getTable() . '.tenant_id', $tenantId);
        }
    }

    /**
     * Prefer the middleware-bound tenant; fall back to the authenticated user's
     * tenant so Livewire sub-requests (e.g. widget polling) stay scoped when the
     * panel middleware stack does not re-bind app('tenant').
     */
    private function resolveTenantId(): ?int
    {
        if (app()->bound('tenant') && app('tenant')) {
            return (int) app('tenant')->id;
        }

        if (Auth::hasUser()) {
            $id = Auth::user()?->tenant_id;

            return $id !== null ? (int) $id : null;
        }

        return null;
    }
}

