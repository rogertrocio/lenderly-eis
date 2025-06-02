<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test for user log in with correct credentials.
     *
     * @return void
     */
    public function test_login_with_valid_credentials(): void
    {
        $user = User::factory()->admin()->create();

        $response = $this->postJson('/authenticate', [
            'email' => 'johndoe@eis.com',
            'password' => 'password',
        ]);

        $response->assertStatus(302)
            ->assertRedirect('/');

        $this->assertAuthenticatedAs($user);
    }

    /**
     * Test for user log in with incorrect credentials.
     *
     * @return void
     */
    public function test_login_with_invalid_credentials(): void
    {
        $response = $this->postJson('/authenticate', [
            'email' => 'johndoe@eis.com',
            'password' => 'passworddd',
        ]);

        $response->assertStatus(422)
            ->assertJson([
                'errors' => [
                    'email' => ['The provided credentials do not match our records.'],
                ]
            ]);

        $this->assertGuest();
    }

    /**
     * Test authenticated user to logged out.
     *
     * @return void
     */
    public function test_logout_authenticated_user(): void
    {
        $user = User::factory()->admin()->create();

        $this->actingAs($user);

        $response = $this->postJson('/logout');

        $response->assertStatus(302)
            ->assertRedirect('/login');

        $this->assertGuest();
    }
}
