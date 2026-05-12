<?php

namespace App\Support;

use App\Models\User;

final class DemoUser
{
    public const EMAIL = 'demo@biztrack.app';

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
