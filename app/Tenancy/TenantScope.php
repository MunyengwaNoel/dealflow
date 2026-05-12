<?php

namespace App\Tenancy;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class TenantScope implements Scope
{
    public function apply(Builder $builder, Model $model): void
    {
        $tenantId = app()->bound('tenant') && app('tenant') ? app('tenant')->id : null;

        if ($tenantId !== null && $model->getTable() !== 'tenants') {
            $builder->where($model->getTable() . '.tenant_id', $tenantId);
        }
    }
}

