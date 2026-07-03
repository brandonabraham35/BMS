<?php

namespace Tests\Feature\Organization;

use App\Models\Branch;
use App\Models\Company;
use App\Models\User;
use App\Models\Workspace;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BranchApiTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;
    protected Company $company;
    protected Workspace $workspace;

    protected function setUp(): void
    {
        parent::setUp();
        $this->workspace = Workspace::factory()->create();
        $this->company = Company::factory()->create(['workspace_id' => $this->workspace->id]);
        $this->user = User::factory()->create([
            'workspace_id' => $this->workspace->id,
            'company_id' => $this->company->id
        ]);
    }

    public function test_can_list_branches()
    {
        Branch::factory()->count(2)->create(['company_id' => $this->company->id]);
        Branch::factory()->create(); // Different company

        $response = $this->actingAs($this->user)->getJson('/api/v1/branches');

        $response->assertStatus(200)
            ->assertJsonCount(2, 'data');
    }

    public function test_can_create_branch()
    {
        $data = [
            'name' => 'Main Branch',
            'code' => 'BR-001',
            'email' => 'main@company.com',
        ];

        $response = $this->actingAs($this->user)->postJson('/api/v1/branches', $data);

        $response->assertStatus(201)
            ->assertJsonPath('data.name', 'Main Branch');

        $this->assertDatabaseHas('branches', [
            'name' => 'Main Branch',
            'company_id' => $this->company->id
        ]);
    }
}
