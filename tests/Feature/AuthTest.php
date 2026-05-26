<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    // ── Login page ────────────────────────────────────────────────────────────

    public function test_login_page_is_accessible_for_guests(): void
    {
        $this->get(route('admin.login'))->assertStatus(200);
    }

    public function test_authenticated_user_is_redirected_from_login_page(): void
    {
        $user = User::factory()->create(['role' => 'admin']);

        $this->actingAs($user)
            ->get(route('admin.login'))
            ->assertRedirect(route('admin.dashboard'));
    }

    // ── Login submission ──────────────────────────────────────────────────────

    public function test_login_with_valid_credentials_authenticates_user(): void
    {
        $user = User::factory()->create([
            'email' => 'admin@example.com',
            'password' => bcrypt('secret123'),
            'role' => 'admin',
        ]);

        $this->post(route('admin.login.post'), [
            'email' => 'admin@example.com',
            'password' => 'secret123',
        ])->assertRedirect(route('admin.dashboard'));

        $this->assertAuthenticatedAs($user);
    }

    public function test_login_with_wrong_password_is_rejected(): void
    {
        User::factory()->create([
            'email' => 'admin@example.com',
            'password' => bcrypt('secret123'),
        ]);

        $this->post(route('admin.login.post'), [
            'email' => 'admin@example.com',
            'password' => 'wrong-password',
        ])->assertSessionHasErrors('email');

        $this->assertGuest();
    }

    public function test_login_with_nonexistent_email_is_rejected(): void
    {
        $this->post(route('admin.login.post'), [
            'email' => 'nobody@example.com',
            'password' => 'password',
        ])->assertSessionHasErrors('email');

        $this->assertGuest();
    }

    public function test_login_requires_email_field(): void
    {
        $this->post(route('admin.login.post'), [
            'email' => '',
            'password' => 'password',
        ])->assertSessionHasErrors('email');
    }

    public function test_login_requires_valid_email_format(): void
    {
        $this->post(route('admin.login.post'), [
            'email' => 'not-an-email',
            'password' => 'password',
        ])->assertSessionHasErrors('email');
    }

    public function test_login_requires_password_field(): void
    {
        $this->post(route('admin.login.post'), [
            'email' => 'admin@example.com',
            'password' => '',
        ])->assertSessionHasErrors('password');
    }

    // ── Logout ────────────────────────────────────────────────────────────────

    public function test_logout_logs_out_authenticated_user(): void
    {
        $user = User::factory()->create(['role' => 'admin']);

        $this->actingAs($user)->post(route('admin.logout'));

        $this->assertGuest();
    }

    public function test_logout_redirects_to_login_page(): void
    {
        $user = User::factory()->create(['role' => 'admin']);

        $this->actingAs($user)
            ->post(route('admin.logout'))
            ->assertRedirect(route('admin.login'));
    }
}
