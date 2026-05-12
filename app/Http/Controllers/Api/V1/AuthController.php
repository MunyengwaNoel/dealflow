<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\Api\V1\Auth\ChangePasswordRequest;
use App\Http\Requests\Api\V1\Auth\LoginRequest;
use App\Http\Requests\Api\V1\Auth\UpdateProfileRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthController extends ApiController
{
    public function login(LoginRequest $request)
    {
        $credentials = $request->only('email', 'password');

        $user = User::query()->where('email', $credentials['email'] ?? '')->first();

        if (! $user || ! Hash::check($credentials['password'] ?? '', $user->password)) {
            return $this->errorResponse('Invalid credentials.', ['email' => ['Invalid credentials.']], 422);
        }

        if (! $user->tenant_id) {
            return $this->errorResponse('This account is not linked to an organization.', ['email' => ['No tenant assigned.']], 422);
        }

        $user->forceFill(['last_login_at' => now()])->saveQuietly();

        $tokenName = $request->string('device_name')->toString() ?: 'api';

        $token = $user->createToken($tokenName)->plainTextToken;

        return $this->successResponse([
            'token' => $token,
            'user' => $user,
            'tenant' => $user->tenant,
        ], 'Logged in.');
    }

    public function logout()
    {
        $user = auth()->user();
        $user?->currentAccessToken()?->delete();

        return $this->successResponse((object) [], 'Logged out.');
    }

    public function refresh()
    {
        $user = auth()->user();
        $current = $user?->currentAccessToken();

        if (! $user || ! $current) {
            return $this->errorResponse('Unauthenticated.', null, 401);
        }

        $tokenName = $current->name ?: 'api';
        $current->delete();

        $token = $user->createToken($tokenName)->plainTextToken;

        return $this->successResponse(['token' => $token], 'Token refreshed.');
    }

    public function me()
    {
        $user = auth()->user();

        return $this->successResponse([
            'user' => $user,
            'tenant' => $user?->tenant,
        ], 'OK');
    }

    public function profile(UpdateProfileRequest $request)
    {
        $user = $request->user();

        $user->fill($request->validated());
        $user->save();

        return $this->successResponse([
            'user' => $user->fresh(),
            'tenant' => $user->tenant,
        ], 'Profile updated.');
    }

    public function password(ChangePasswordRequest $request)
    {
        $user = $request->user();

        if (! Hash::check($request->string('current_password')->toString(), $user->password)) {
            return $this->errorResponse('Current password is incorrect.', ['current_password' => ['Current password is incorrect.']], 422);
        }

        $user->password = $request->string('password')->toString();
        $user->save();

        return $this->successResponse((object) [], 'Password updated.');
    }
}

