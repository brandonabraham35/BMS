<?php

namespace Tests\Feature\Organization;

use App\Models\Department;
use App\Models\Branch;
use App\Models\Company;
use App\Models\User;
use App\Models\Workspace;
use App\Models\Team;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TeamApiTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;
    protected Department $department;

    protected function setUp(): void
    {
        parent::setUp();
        $workspace = Workspace::factory()->create();
        $company = Company::factory()->create(['workspace_id' => $workspace->id]);
        $branch = Branch::factory()->create(['company_id' => $company->id]);
        $this->department = Department::factory()->create([
            'workspace_id' => $workspace->id,
            'company_id' => $company->id,
            'branch_id' => $branch->id
        ]);
        $this->user = User::factory()->create([
            'workspace_id' => $workspace->id,
            'company_id' => $company->id,
            'branch_id' => $branch->id
        ]);
    }

    public function test_can_create_team()
    {
        $data = [
            'department_id' => $this->department->id,
            'name' => 'Sales Team',
            'description' => 'Direct sales group'
        ];

        $response = $this->actingAs($this->user)->postJson('/api/v1/teams', $data);

        $response->assertStatus(201);
        $this->assertDatabaseHas('teams', [
            'name' => 'Sales Team',
            'department_id' => $this->department->id
        ]);
    }
}
