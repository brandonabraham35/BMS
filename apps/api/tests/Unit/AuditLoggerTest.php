<?php

namespace Tests\Unit;

use App\Models\Company;
use App\Services\AuditLogger;
use App\Services\CompanyContext;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Tests\TestCase;

class AuditLoggerTest extends TestCase
{
    use RefreshDatabase;

    public function test_audit_log_creation(): void
    {
        $company = Company::create(['name' => 'Test Co', 'slug' => 'test-co']);
        $context = new CompanyContext;
        $context->setCompany($company);

        $request = Request::create('/test', 'GET');

        $logger = new AuditLogger($context, $request);
        $logger->log('created', $company, null, $company->toArray());

        $this->assertDatabaseHas('audit_logs', [
            'company_id' => $company->id,
            'action' => 'created',
            'entity_type' => Company::class,
            'entity_id' => $company->id,
        ]);
    }
}
