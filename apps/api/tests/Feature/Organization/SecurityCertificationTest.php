<?php

namespace Tests\Feature\Organization;

use App\Models\Branch;
use App\Models\Company;
use App\Models\Department;
use App\Models\Team;
use App\Models\User;
use App\Models\Workspace;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SecurityCertificationTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;
    protected Workspace $workspace;
    protected Company $company;

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

    public function test_cannot_access_other_workspace_company()
    {
        $otherWorkspace = Workspace::factory()->create();
        $otherCompany = Company::factory()->create(['workspace_id' => $otherWorkspace->id]);

        $response = $this->actingAs($this->user)->getJson("/api/v1/companies/{$otherCompany->id}");

        $response->assertStatus(403);
    }

    public function test_cannot_access_other_workspace_branch()
    {
        $otherWorkspace = Workspace::factory()->create();
        $otherCompany = Company::factory()->create(['workspace_id' => $otherWorkspace->id]);
        $otherBranch = Branch::factory()->create(['company_id' => $otherCompany->id]);

        $response = $this->actingAs($this->user)->getJson("/api/v1/branches/{$otherBranch->id}");

        $response->assertStatus(403);
    }

    public function test_cannot_list_other_workspace_resources()
    {
        $otherWorkspace = Workspace::factory()->create();
        Company::factory()->count(5)->create(['workspace_id' => $otherWorkspace->id]);

        $response = $this->actingAs($this->user)->getJson("/api/v1/companies");

        $response->assertStatus(200);
        // User's own company + user factory created company?
        // Let's count properly
        $count = Company::where('workspace_id', $this->workspace->id)->count();
        $response->assertJsonCount($count, 'data');
    }

    public function test_cannot_update_other_workspace_settings()
    {
        $otherWorkspace = Workspace::factory()->create();

        // This should fail because WorkspaceMiddleware checks TenantContext,
        // and TenantContext is resolved from Auth User's workspace_id.
        // Even if we try to bypass by headers (not implemented yet), it must stay within Auth boundaries.

        $this->actingAs($this->user);

        // We don't have a way to specify "other workspace" in the current PATCH /workspace/settings
        // since it automatically uses the authenticated user's workspace.
        // This is a form of inherent isolation.

        $response = $this->patchJson('/api/v1/workspace/settings', [
            'settings' => [
                ['key' => 'hack', 'value' => 'pwned', 'type' => 'string']
            ]
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseMissing('settings', [
            'workspace_id' => $otherWorkspace->id,
            'key' => 'hack'
        ]);
    }
}
