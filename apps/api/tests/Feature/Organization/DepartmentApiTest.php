<?php

namespace Tests\Feature\Organization;

use App\Models\Department;
use App\Models\Branch;
use App\Models\Company;
use App\Models\User;
use App\Models\Workspace;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DepartmentApiTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;
    protected Branch $branch;

    protected function setUp(): void
    {
        parent::setUp();
        $workspace = Workspace::factory()->create();
        $company = Company::factory()->create(['workspace_id' => $workspace->id]);
        $this->branch = Branch::factory()->create(['company_id' => $company->id]);
        $this->user = User::factory()->create([
            'workspace_id' => $workspace->id,
            'company_id' => $company->id,
            'branch_id' => $this->branch->id
        ]);
    }

    public function test_can_create_department()
    {
        $data = [
            'name' => 'Kitchen',
            'code' => 'KIT'
        ];

        $response = $this->actingAs($this->user)->postJson('/api/v1/departments', $data);

        $response->assertStatus(201);
        $this->assertDatabaseHas('departments', [
            'name' => 'Kitchen',
            'branch_id' => $this->branch->id
        ]);
    }
}
