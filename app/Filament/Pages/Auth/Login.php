<?php

namespace App\Filament\Pages\Auth;

use App\Support\DemoUser;
use Filament\Pages\Auth\Login as BaseLogin;

class Login extends BaseLogin
{
    public function mount(): void
    {
        parent::mount();

        $this->form->fill([
            'email' => DemoUser::EMAIL,
            'password' => 'password',
        ]);
    }
}
