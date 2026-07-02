<?php

namespace Tests\Feature\IAM;

use Tests\TestCase;
use App\Models\User;
use App\Models\Company;
use App\Models\Invitation;
use Illuminate\Foundation\Testing\RefreshDatabase;

class InvitationTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_invite_others(): void
    {
        $company = Company::create(['name' => 'Test Co', 'slug' => 'test-co']);
        $admin = User::factory()->create(['company_id' => $company->id]);

        $this->actingAs($admin, 'sanctum');

        $response = $this->postJson('/api/v1/invitations', [
            'email' => 'newuser@example.com',
            'role_slug' => 'staff',
        ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('invitations', [
            'email' => 'newuser@example.com',
            'company_id' => $company->id,
        ]);
    }
}
