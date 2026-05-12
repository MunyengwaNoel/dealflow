<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;

abstract class ApiController extends Controller
{
    use AuthorizesRequests, ValidatesRequests;

    protected function successResponse(mixed $data = null, string $message = 'OK', array $meta = null, int $status = 200): JsonResponse
    {
        $payload = [
            'success' => true,
            'data' => $data ?? (object) [],
            'message' => $message,
        ];

        if ($meta !== null) {
            $payload['meta'] = $meta;
        }

        return response()->json($payload, $status);
    }

    protected function errorResponse(string $message, array $errors = null, int $status = 400, array $extra = null): JsonResponse
    {
        $payload = [
            'success' => false,
            'message' => $message,
        ];

        if ($errors !== null) {
            $payload['errors'] = $errors;
        }

        if ($extra !== null) {
            $payload = array_merge($payload, $extra);
        }

        return response()->json($payload, $status);
    }
}

