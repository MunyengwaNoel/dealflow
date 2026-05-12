<?php

namespace App\Services;

use App\Models\ServiceTemplate;
use App\Models\Tenant;
use App\Models\User;
use App\Support\DealFlowTemplateCatalog;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class TenantService
{
    /**
     * @return array{tenant: Tenant, owner: User}
     */
    public function createTenant(array $input): array
    {
        return DB::transaction(function () use ($input) {
            $slug = $input['slug'] ?? Str::slug($input['name']).'-'.Str::lower(Str::random(4));

            $tenant = Tenant::query()->create([
                'name' => $input['name'],
                'slug' => $slug,
                'plan' => $input['plan'] ?? 'free',
                'email' => $input['email'] ?? null,
                'phone' => $input['phone'] ?? null,
            ]);

            $owner = User::query()->create([
                'tenant_id' => $tenant->id,
                'name' => $input['owner_name'],
                'email' => $input['owner_email'],
                'phone' => $input['owner_phone'] ?? null,
                'password' => $input['owner_password'],
                'role' => 'owner',
            ]);

            $tenant->update(['owner_id' => $owner->id]);

            $this->seedServiceTemplates($tenant->fresh(), $owner->id);

            return ['tenant' => $tenant->fresh(), 'owner' => $owner->fresh()];
        });
    }

    public function seedServiceTemplates(Tenant $tenant, ?int $createdByUserId = null): void
    {
        $this->ensureDealFlowCatalog($tenant, $createdByUserId ?? $tenant->owner_id);
    }

    public function ensureDealFlowCatalog(Tenant $tenant, ?int $createdByUserId = null): void
    {
        $uid = $createdByUserId ?? $tenant->owner_id;
        foreach (DealFlowTemplateCatalog::seedRows() as $row) {
            ServiceTemplate::query()->updateOrCreate(
                [
                    'tenant_id' => $tenant->id,
                    'template_code' => $row['template_code'],
                ],
                array_merge($row, [
                    'tenant_id' => $tenant->id,
                    'is_active' => true,
                    'status' => 'active',
                    'created_by' => $uid,
                ])
            );
        }
    }
}
