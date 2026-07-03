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
        $this->actingAs($user, 'sanctum');

        $user->createToken('device-1');
        $user->createToken('device-2');

        $response = $this->getJson('/api/v1/sessions');

        $response->assertStatus(200);
        $response->assertJsonStructure(['data']);
    }

    public function test_user_can_terminate_other_sessions(): void
    {
        $user = User::factory()->create();
        $token = $user->createToken('current-token')->plainTextToken;
        $user->createToken('other-device');

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->deleteJson('/api/v1/sessions/other');

        $response->assertStatus(200);
        $this->assertEquals(1, $user->tokens()->count());
    }
}
