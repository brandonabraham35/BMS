<?php

namespace Tests\Feature\Organization;

use App\Models\Branch;
use App\Models\Company;
use App\Models\User;
use App\Models\Workspace;
use App\Models\UserTransfer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserTransferTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected Workspace $workspace;
    protected Company $company;

    protected function setUp(): void
    {
        parent::setUp();
        $this->workspace = Workspace::factory()->create();
        $this->company = Company::factory()->create(['workspace_id' => $this->workspace->id]);
        $this->admin = User::factory()->create([
            'workspace_id' => $this->workspace->id,
            'company_id' => $this->company->id,
            'status' => 'active'
        ]);
    }

    public function test_can_transfer_user_to_another_branch()
    {
        $branch1 = Branch::factory()->create(['company_id' => $this->company->id]);
        $branch2 = Branch::factory()->create(['company_id' => $this->company->id]);

        $user = User::factory()->create([
            'workspace_id' => $this->workspace->id,
            'company_id' => $this->company->id,
            'branch_id' => $branch1->id
        ]);

        $response = $this->actingAs($this->admin)->postJson("/api/v1/users/{$user->id}/transfers", [
            'workspace_id' => $this->workspace->id,
            'company_id' => $this->company->id,
            'branch_id' => $branch2->id,
            'reason' => 'Promotion'
        ]);

        $response->assertStatus(200);
        $this->assertEquals($branch2->id, $user->fresh()->branch_id);

        $this->assertDatabaseHas('user_transfers', [
            'user_id' => $user->id,
            'from_branch_id' => $branch1->id,
            'to_branch_id' => $branch2->id,
            'reason' => 'Promotion'
        ]);

        $this->assertDatabaseHas('audit_logs', [
            'action' => 'user.transferred',
            'entity_id' => $user->id
        ]);
    }
}
