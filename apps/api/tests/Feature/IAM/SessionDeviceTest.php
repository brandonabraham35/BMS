<?php

namespace Tests\Feature\IAM;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SessionDeviceTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_list_sessions(): void
    {
        $user = User::factory()->create();
        $user->createToken('device-1');
        $user->createToken('device-2');

        $this->actingAs($user, 'sanctum');

        $response = $this->getJson('/api/v1/sessions');

        $response->assertStatus(200)
            ->assertJsonCount(3, 'data'); // 2 + 1 from actingAs
    }

    public function test_user_can_terminate_other_sessions(): void
    {
        $user = User::factory()->create();
        $user->createToken('other-device');

        $this->actingAs($user, 'sanctum');

        $response = $this->deleteJson('/api/v1/sessions/other');

        $response->assertStatus(200);
        $this->assertEquals(1, $user->tokens()->count());
    }
}
