<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\TenantService;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * @throws ValidationException
     */
    public function store(Request $request, TenantService $tenantService): RedirectResponse
    {
        $validated = $request->validate([
            'business_name' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'string', 'lowercase', 'email', 'max:255', Rule::unique(User::class, 'email')],
            'phone' => ['required', 'string', 'max:50', Rule::unique(User::class, 'phone')],
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        $email = filled($validated['email']) ? $validated['email'] : null;

        $ownerEmail = $email ?? $this->syntheticLoginEmail($validated['phone']);

        $created = $tenantService->createTenant([
            'name' => $validated['business_name'],
            'email' => $email,
            'phone' => $validated['phone'],
            'owner_name' => $validated['business_name'],
            'owner_email' => $ownerEmail,
            'owner_phone' => $validated['phone'],
            'owner_password' => $validated['password'],
        ]);

        $owner = $created['owner'];

        event(new Registered($owner));

        Auth::login($owner);

        return redirect()->route('filament.admin.pages.dashboard');
    }

    /**
     * Filament login uses email; when the user skips email we assign a unique internal address.
     */
    private function syntheticLoginEmail(string $phone): string
    {
        $digits = preg_replace('/\D+/', '', $phone) ?: Str::lower(Str::random(10));

        $base = 'u'.$digits.'@signup.dealflow.local';
        $candidate = $base;
        $suffix = 0;

        while (User::query()->where('email', $candidate)->exists()) {
            $suffix++;
            $candidate = 'u'.$digits.'-'.$suffix.'@signup.dealflow.local';
        }

        return $candidate;
    }
}
