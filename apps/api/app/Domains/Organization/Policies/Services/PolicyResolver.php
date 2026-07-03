<?php

namespace App\Domains\Organization\Policies\Services;

use App\Models\OrganizationPolicy;
use App\Domains\Organization\Tenant\TenantContext;

class PolicyResolver
{
    public function __construct(
        protected TenantContext $tenantContext
    ) {}

    public function resolve(string $type): ?OrganizationPolicy
    {
        // Hierarchy: Branch (via Company) -> Company -> Workspace -> Platform
        // For now, scoping by Company as defined in migration
        return OrganizationPolicy::where('type', $type)
            ->where('company_id', $this->tenantContext->getCompanyId())
            ->where('is_active', true)
            ->first();
    }
}
