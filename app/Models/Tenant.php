<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tenant extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'plan',
        'plan_expires_at',
        'logo',
        'address',
        'phone',
        'email',
        'website',
        'owner_id',
        'stripe_customer_id',
        'settings',
    ];

    protected $casts = [
        'plan_expires_at' => 'datetime',
        'settings' => 'array',
    ];

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function isPro(): bool
    {
        return ($this->plan ?? 'free') === 'pro';
    }

    public function can(string $feature): bool
    {
        if ($this->isPro()) {
            return true;
        }

        return match ($feature) {
            'quotes',
            'deals',
            'cashflow',
            'pdf',
            'email_send',
            'service_templates_manage' => false,
            'team' => false,
            default => true,
        };
    }

    public function clientCount(): int
    {
        return Client::query()->where('tenant_id', $this->id)->count();
    }

    public function canCreateClient(): bool
    {
        if ($this->isPro()) {
            return true;
        }

        return $this->clientCount() < 20;
    }

    public function userCount(): int
    {
        return User::query()->where('tenant_id', $this->id)->count();
    }

    public function canInviteTeamMember(): bool
    {
        return $this->isPro();
    }
}
