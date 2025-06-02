<?php

namespace Tests\Feature;

use App\Enums\Action;
use App\Enums\Module;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ProfileTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test the profile response of the authenticated user.
     *
     * @return void
     */
    public function test_authenticated_user_to_get_profile_response(): void
    {
        $role = Role::create(['name' => 'Administrator']);
        $permission = Permission::create(['name' => Module::DASHBOARD . '.' . Action::ACCESS]);
        $user = User::factory()->admin()->create();

        $role->permissions()->attach($permission->id);
        $user->roles()->attach($role->id);

        $this->actingAs($user);

        $response = $this->getJson('/profile');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'first_name',
                    'first_name',
                    'last_name',
                    'email',
                    'phone',
                    'job',
                    'roles' => [
                        ['id', 'name', 'permissions']
                    ],
                    'latest_attendance',
                ]
            ]);
    }
}
