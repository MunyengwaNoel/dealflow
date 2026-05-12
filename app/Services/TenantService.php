<?php

namespace App\Services;

use App\Models\ServiceTemplate;
use App\Models\Tenant;
use App\Models\User;
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
                'password' => $input['owner_password'],
                'role' => 'owner',
            ]);

            $tenant->update(['owner_id' => $owner->id]);

            $this->seedServiceTemplates($tenant);

            return ['tenant' => $tenant->fresh(), 'owner' => $owner->fresh()];
        });
    }

    public function seedServiceTemplates(Tenant $tenant): void
    {
        foreach (self::defaultTemplateRows() as $row) {
            ServiceTemplate::query()->create(array_merge($row, [
                'tenant_id' => $tenant->id,
                'is_active' => true,
            ]));
        }
    }

    /**
     * @return list<array{name: string, description: string, category: string, cost_price: string, sell_price: string}>
     */
    public static function defaultTemplateRows(): array
    {
        return [
            ['name' => 'Consulting (hour)', 'description' => 'Professional consulting', 'category' => 'Services', 'cost_price' => '40.00', 'sell_price' => '85.00'],
            ['name' => 'Site visit', 'description' => 'On-site assessment', 'category' => 'Services', 'cost_price' => '25.00', 'sell_price' => '55.00'],
            ['name' => 'Installation', 'description' => 'Standard installation', 'category' => 'Install', 'cost_price' => '120.00', 'sell_price' => '220.00'],
            ['name' => 'Maintenance retainer', 'description' => 'Monthly maintenance', 'category' => 'Recurring', 'cost_price' => '150.00', 'sell_price' => '299.00'],
            ['name' => 'Emergency call-out', 'description' => 'After-hours support', 'category' => 'Support', 'cost_price' => '60.00', 'sell_price' => '150.00'],
        ];
    }
}
