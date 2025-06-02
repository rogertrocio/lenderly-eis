<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CheckInAndOutTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A authenticated user that can check in if no record of checked in today.
     *
     * @return void
     */
    public function test_user_can_check_in_if_no_record_of_checked_in_today(): void
    {
        $user = User::factory()->admin()->create();

        $this->actingAs($user);

        $response = $this->postJson('/check-in');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => ['id', 'user_id', 'date', 'check_in', 'is_active']
            ]);

        $this->assertDatabaseHas('attendances', [
            'user_id' => $user->id,
            'date' => now()->toDateString(),
        ]);
    }

    /**
     * Test authenticated that cannot check twice on the same day.
     *
     * @return void
     */
    public function test_user_cannot_check_in_on_same_day_twice(): void
    {
        $user = User::factory()->admin()->create();

        $this->actingAs($user);

        $user->attendances()->create([
            'date' => now()->toDateString(),
            'check_in' => now()->toDateString(),
            'is_active' => true,
        ]);

        $response = $this->postJson('/check-in');

        $response->assertStatus(422)
            ->assertJson(['message' => 'You are already checked in today.']);
    }
}
