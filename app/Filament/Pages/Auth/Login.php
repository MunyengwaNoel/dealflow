<?php

namespace App\Filament\Pages\Auth;

use App\Support\DemoUser;
use Filament\Facades\Filament;
use Filament\Http\Responses\Auth\Contracts\LoginResponse;
use Filament\Pages\Auth\Login as BaseLogin;

class Login extends BaseLogin
{
    public function mount(): void
    {
        DemoUser::ensureSeededForLogin();

        parent::mount();

        $this->form->fill([
            'email' => DemoUser::EMAIL,
            'password' => 'password',
        ]);
    }

    public function authenticate(): ?LoginResponse
    {
        $response = parent::authenticate();

        if ($response) {
            Filament::auth()->user()?->forceFill(['last_login_at' => now()])->saveQuietly();
        }

        return $response;
    }
}
