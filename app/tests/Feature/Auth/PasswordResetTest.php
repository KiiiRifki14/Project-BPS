<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PasswordResetTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);
    }

    /**
     * Dalam sistem BPS, reset password dilakukan oleh Admin melalui menu Manajemen Pengguna.
     */
    public function test_admin_can_reset_user_password(): void
    {
        $admin    = User::where('role', 'ADMIN')->first();
        $operator = User::where('role', 'OPERATOR')->first();

        $response = $this->actingAs($admin)->post(route('users.reset-password', $operator), [
            'password'              => 'NewPassword2026!',
            'password_confirmation' => 'NewPassword2026!',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        // Verifikasi password baru di DB telah di-hash dengan benar
        $this->assertTrue(\Illuminate\Support\Facades\Hash::check('NewPassword2026!', $operator->fresh()->password));
    }

    public function test_operator_cannot_reset_passwords(): void
    {
        $operator = User::where('role', 'OPERATOR')->first();

        $response = $this->actingAs($operator)->post(route('users.reset-password', $operator), [
            'password'              => 'NewPassword2026!',
            'password_confirmation' => 'NewPassword2026!',
        ]);

        $response->assertForbidden();
    }
}
