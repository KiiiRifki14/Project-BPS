<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    /**
     * OWASP A01: Broken Access Control / Vulnerable Components
     * Registrasi mandiri harus dinonaktifkan (404 Not Found).
     */
    public function test_registration_screen_is_disabled(): void
    {
        $response = $this->get('/register');
        $response->assertStatus(404);
    }

    public function test_new_users_cannot_self_register(): void
    {
        $response = $this->post('/register', [
            'name' => 'Test User',
            'nip_username' => 'testuser01',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertStatus(404);
        $this->assertGuest();
    }
}
