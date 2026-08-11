<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EmailVerificationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);
    }

    /**
     * Dalam sistem NIP/Username BPS, pengguna langsung aktif tanpa verifikasi email.
     */
    public function test_user_can_access_dashboard_without_email_verification(): void
    {
        $operator = User::where('role', 'OPERATOR')->first();

        $response = $this->actingAs($operator)->get('/dashboard');
        $response->assertOk();
    }
}
