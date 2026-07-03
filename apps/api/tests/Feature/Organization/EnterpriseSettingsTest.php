<?php

namespace Tests\Feature\Organization;

use App\Domains\Organization\Tenant\TenantContext;
use App\Models\Branch;
use App\Models\Company;
use App\Models\User;
use App\Models\Workspace;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EnterpriseSettingsTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;
    protected Workspace $workspace;
    protected Company $company;
    protected Branch $branch;

    protected function setUp(): void
    {
        parent::setUp();
        $this->workspace = Workspace::factory()->create();
        $this->company = Company::factory()->create(['workspace_id' => $this->workspace->id]);
        $this->branch = Branch::factory()->create(['company_id' => $this->company->id]);
        $this->user = User::factory()->create([
            'workspace_id' => $this->workspace->id,
            'company_id' => $this->company->id,
            'branch_id' => $this->branch->id
        ]);
    }

    public function test_settings_inheritance_works()
    {
        $settingsService = app(\App\Services\SettingsService::class);
        $tenantContext = app(TenantContext::class);

        // Manually set context because middleware won't run for direct service calls
        $tenantContext->setUser($this->user);
        $tenantContext->setWorkspace($this->workspace);
        $tenantContext->setCompany($this->company);
        $tenantContext->setBranch($this->branch);

        // 1. Set at Workspace level
        $settingsService->set('theme', 'dark', ['workspace_id' => $this->workspace->id]);
        $this->assertEquals('dark', $settingsService->get('theme'));

        // 2. Override at Company level
        $settingsService->set('theme', 'blue', ['company_id' => $this->company->id]);
        $this->assertEquals('blue', $settingsService->get('theme'));

        // 3. Override at Branch level
        $settingsService->set('theme', 'green', ['branch_id' => $this->branch->id]);
        $this->assertEquals('green', $settingsService->get('theme'));
    }

    public function test_api_can_update_context_settings()
    {
        $this->actingAs($this->user);

        $response = $this->patchJson('/api/v1/workspace/settings', [
            'settings' => [
                ['key' => 'timezone', 'value' => 'Africa/Kampala', 'type' => 'string']
            ]
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('settings', [
            'workspace_id' => $this->workspace->id,
            'key' => 'timezone',
            'value' => 'Africa/Kampala'
        ]);
    }
}
