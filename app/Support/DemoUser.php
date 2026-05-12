<?php

namespace App\Support;

use App\Models\User;
use Database\Seeders\DemoTenantSeeder;
use Throwable;

final class DemoUser
{
    public const EMAIL = 'demo@biztrack.app';

    /**
     * Ensure the demo owner exists so prepopulated admin login works after migrate without a manual seed.
     */
    public static function ensureSeededForLogin(): void
    {
        $ready = User::query()
            ->where('email', self::EMAIL)
            ->whereNotNull('tenant_id')
            ->exists();

        if ($ready) {
            return;
        }

        try {
            (new DemoTenantSeeder)->run();
        } catch (Throwable $e) {
            report($e);
        }
    }

    public static function isDemo(?User $user = null): bool
    {
        $user ??= auth()->user();

        return $user && $user->email === self::EMAIL;
    }

    public static function abortIfDemo(): void
    {
        if (self::isDemo()) {
            abort(403, 'Demo account is read-only.');
        }
    }
}
