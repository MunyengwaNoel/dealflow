<?php

namespace App\Http\Controllers\Api\V1\Concerns;

use App\Http\Controllers\Api\V1\ApiController;
use App\Support\DemoUser;
use Illuminate\Http\Request;

trait RejectsDemoWrites
{
    protected function rejectIfDemo(Request $request): ?\Illuminate\Http\JsonResponse
    {
        if (DemoUser::isDemo($request->user())) {
            /** @var ApiController $this */
            return $this->errorResponse('Demo account is read-only.', null, 403);
        }

        return null;
    }
}
