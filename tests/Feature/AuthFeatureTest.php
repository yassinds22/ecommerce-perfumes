<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class AuthFeatureTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function user_can_login_with_correct_credentials()
    {
        $user = User::factory()->create([
            'password' => bcrypt($password = 'i-love-laravel'),
        ]);

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => $password,
        ]);

        $response->assertRedirect(route('home'));
        $this->assertAuthenticatedAs($user);
    }

    #[Test]
    public function user_cannot_login_with_incorrect_password()
    {
        $user = User::factory()->create([
            'password' => bcrypt('i-love-laravel'),
        ]);

        $response = $this->from('/login')->post('/login', [
            'email' => $user->email,
            'password' => 'invalid-password',
        ]);

        $response->assertRedirect('/login');
        $this->assertGuest();
    }

    #[Test]
    public function admin_can_access_admin_dashboard()
    {
        $admin = User::factory()->create(['role' => 'Admin']);

        $response = $this->actingAs($admin)->get('/admin');

        $response->assertStatus(200);
    }

    #[Test]
    public function regular_user_cannot_access_admin_dashboard()
    {
        $user = User::factory()->create(['role' => 'Customer']);

        $response = $this->actingAs($user)->get('/admin');

        $response->assertStatus(403);
    }
}
