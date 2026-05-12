<?php

namespace App\Http\Middleware;

use App\Models\Tenant;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PlanMiddleware
{
    public function handle(Request $request, Closure $next, string $feature): Response
    {
        /** @var Tenant|null $tenant */
        $tenant = app('tenant');

        if (! $tenant || ! $tenant->can($feature)) {
            return response()->json([
                'success' => false,
                'message' => 'Upgrade to Pro to access this feature',
                'upgrade_url' => url('/admin'),
            ], 403);
        }

        return $next($request);
    }
}
