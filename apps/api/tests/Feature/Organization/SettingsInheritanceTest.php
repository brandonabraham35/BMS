<?php

namespace Tests\Feature\Organization;

use App\Domains\Organization\Tenant\TenantContext;
use App\Models\Branch;
use App\Models\Company;
use App\Models\User;
use App\Models\Workspace;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SettingsInheritanceTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;
    protected Workspace $workspace;
    protected Company $parentCompany;
    protected Company $childCompany;
    protected Branch $branch;

    protected function setUp(): void
    {
        parent::setUp();
        $this->workspace = Workspace::factory()->create();
        $this->parentCompany = Company::factory()->create(['workspace_id' => $this->workspace->id]);
        $this->childCompany = Company::factory()->create([
            'workspace_id' => $this->workspace->id,
            'parent_company_id' => $this->parentCompany->id
        ]);
        $this->branch = Branch::factory()->create(['company_id' => $this->childCompany->id]);
        $this->user = User::factory()->create([
            'workspace_id' => $this->workspace->id,
            'company_id' => $this->childCompany->id,
            'branch_id' => $this->branch->id
        ]);
    }

    public function test_settings_fallback_through_deep_hierarchy()
    {
        $settingsService = app(\App\Services\SettingsService::class);
        $tenantContext = app(TenantContext::class);

        // Manually set context
        $tenantContext->setUser($this->user);
        $tenantContext->setWorkspace($this->workspace);
        $tenantContext->setCompany($this->childCompany);
        $tenantContext->setBranch($this->branch);

        // 1. Set at Workspace level -> Should be visible at Branch
        $settingsService->set('global_logo', 'workspace.png', ['workspace_id' => $this->workspace->id]);
        $this->assertEquals('workspace.png', $settingsService->get('global_logo'));

        // 2. Set at Parent Company level -> Should be visible at Branch
        $settingsService->set('theme', 'enterprise-blue', ['company_id' => $this->parentCompany->id]);
        $this->assertEquals('enterprise-blue', $settingsService->get('theme'));

        // 3. Override at Child Company level -> Should be visible at Branch
        $settingsService->set('theme', 'subsidiary-green', ['company_id' => $this->childCompany->id]);
        $this->assertEquals('subsidiary-green', $settingsService->get('theme'));

        // 4. Override at Branch level
        $settingsService->set('theme', 'branch-red', ['branch_id' => $this->branch->id]);
        $this->assertEquals('branch-red', $settingsService->get('theme'));

        // 5. Global logo still falls back to Workspace
        $this->assertEquals('workspace.png', $settingsService->get('global_logo'));
    }
}
