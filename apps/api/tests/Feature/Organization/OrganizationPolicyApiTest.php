<?php

namespace Tests\Feature\Organization;

use App\Models\OrganizationPolicy;
use App\Models\Company;
use App\Models\User;
use App\Models\Workspace;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrganizationPolicyApiTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;
    protected Company $company;

    protected function setUp(): void
    {
        parent::setUp();
        $workspace = Workspace::factory()->create();
        $this->company = Company::factory()->create(['workspace_id' => $workspace->id]);
        $this->user = User::factory()->create([
            'workspace_id' => $workspace->id,
            'company_id' => $this->company->id
        ]);
    }

    public function test_can_create_policy()
    {
        $data = [
            'name' => 'Working Days',
            'type' => 'working_days',
            'rules' => ['monday', 'tuesday'],
            'is_active' => true
        ];

        $response = $this->actingAs($this->user)->postJson('/api/v1/organization/policies', $data);

        $response->assertStatus(201);
        $this->assertDatabaseHas('organization_policies', [
            'name' => 'Working Days',
            'company_id' => $this->company->id
        ]);
    }
}
