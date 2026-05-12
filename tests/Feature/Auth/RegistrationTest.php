<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_screen_can_be_rendered(): void
    {
        $response = $this->get('/register');

        $response->assertStatus(200);
    }

    public function test_new_users_can_register_with_email(): void
    {
        $response = $this->post('/register', [
            'business_name' => 'Acme Trading',
            'email' => 'owner@acme.co.zw',
            'phone' => '+263771234567',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(route('filament.admin.pages.dashboard', absolute: false));

        $this->assertDatabaseHas('tenants', [
            'name' => 'Acme Trading',
        ]);

        $user = User::query()->where('email', 'owner@acme.co.zw')->first();
        $this->assertNotNull($user);
        $this->assertSame('+263771234567', $user->phone);
        $this->assertNotNull($user->tenant_id);
    }

    public function test_new_users_can_register_without_email(): void
    {
        $this->post('/register', [
            'business_name' => 'Solo Biz',
            'email' => '',
            'phone' => '+263772000001',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $this->assertAuthenticated();

        $user = User::query()->where('phone', '+263772000001')->first();
        $this->assertNotNull($user);
        $this->assertStringEndsWith('@signup.dealflow.local', (string) $user->email);
    }
}
