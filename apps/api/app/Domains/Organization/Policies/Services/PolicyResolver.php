<?php

namespace App\Domains\Organization\Policies\Services;

use App\Models\OrganizationPolicy;
use App\Domains\Organization\Tenant\TenantContext;
use App\Domains\Organization\Policies\Contracts\PolicyRepositoryInterface;

class PolicyResolver
{
    public function __construct(
        protected PolicyRepositoryInterface $repository,
        protected TenantContext $tenantContext
    ) {}

    public function resolve(string $type): ?OrganizationPolicy
    {
        // Hierarchy: Company -> Workspace (future) -> Platform
        $contexts = $this->getHierarchyContexts();

        foreach ($contexts as $context) {
            $policy = $this->repository->find($type, $context);
            if ($policy) {
                return $policy;
            }
        }

        return null;
    }

    protected function getHierarchyContexts(): array
    {
        $contexts = [];

        // 1. Company
        if ($this->tenantContext->getCompanyId()) {
            $contexts[] = ['company_id' => $this->tenantContext->getCompanyId()];
        }

        // 2. Workspace (future)
        // if ($this->tenantContext->getWorkspaceId()) { ... }

        // 3. Platform
        $contexts[] = [];

        return $contexts;
    }
}
