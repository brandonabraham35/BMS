<?php

namespace Tests\Unit;

use App\Models\Company;
use App\Models\Workspace;
use App\Services\AuditLogger;
use App\Domains\Organization\Tenant\TenantContext;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Tests\TestCase;

class AuditLoggerTest extends TestCase
{
    use RefreshDatabase;

    public function test_audit_log_creation(): void
    {
        $workspace = Workspace::factory()->create();
        $company = Company::factory()->create(['workspace_id' => $workspace->id]);

        $context = new TenantContext;
        $context->setWorkspace($workspace);
        $context->setCompany($company);

        $request = Request::create('/test', 'GET');

        $logger = new AuditLogger($context, $request);
        $logger->log('created', $company, null, $company->toArray());

        $this->assertDatabaseHas('audit_logs', [
            'workspace_id' => $workspace->id,
            'company_id' => $company->id,
            'action' => 'created',
            'entity_type' => Company::class,
            'entity_id' => $company->id,
        ]);
    }
}
