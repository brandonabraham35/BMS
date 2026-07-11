<?php

namespace Tests\Feature\Organization;

use App\Models\Company;
use App\Models\User;
use App\Models\Workspace;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HierarchyEdgeCaseTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected Workspace $workspace;

    protected function setUp(): void
    {
        parent::setUp();
        $this->workspace = Workspace::factory()->create();
        $this->admin = User::factory()->create([
            'workspace_id' => $this->workspace->id
        ]);
    }

    public function test_company_cannot_be_its_own_parent()
    {
        $company = Company::factory()->create(['workspace_id' => $this->workspace->id]);

        $response = $this->actingAs($this->admin)->patchJson("/api/v1/companies/{$company->id}", [
            'parent_company_id' => $company->id
        ]);

        $response->assertStatus(422);
        $this->assertNull($company->fresh()->parent_company_id);
    }

    public function test_circular_reference_prevention()
    {
        $parent = Company::factory()->create(['workspace_id' => $this->workspace->id]);
        $child = Company::factory()->create([
            'workspace_id' => $this->workspace->id,
            'parent_company_id' => $parent->id
        ]);

        // Attempt to make parent a child of its own child
        $response = $this->actingAs($this->admin)->patchJson("/api/v1/companies/{$parent->id}", [
            'parent_company_id' => $child->id
        ]);

        $response->assertStatus(422);
        $response->assertJsonFragment(['message' => 'Circular hierarchy detected']);
    }
}
