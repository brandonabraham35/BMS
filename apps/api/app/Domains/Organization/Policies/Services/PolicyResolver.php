<?php

namespace App\Domains\Organization\Policies\Services;

use App\Models\OrganizationPolicy;
use App\Domains\Organization\Tenant\TenantContext;
use App\Domains\Organization\Policies\Contracts\PolicyRepositoryInterface;
use App\Domains\Organization\Caching\Contracts\PolicyCacheInterface;

class PolicyResolver
{
    public function __construct(
        protected PolicyRepositoryInterface $repository,
        protected TenantContext $tenantContext,
        protected PolicyCacheInterface $cache
    ) {}

    public function resolve(string $type): ?OrganizationPolicy
    {
        $contexts = $this->getHierarchyContexts();

        foreach ($contexts as $context) {
            $cached = $this->cache->get($type, $context);
            if ($cached) {
                return $cached;
            }

            $policy = $this->repository->find($type, $context);
            if ($policy) {
                $this->cache->put($type, $policy, $context);
                return $policy;
            }
        }

        return null;
    }

    protected function getHierarchyContexts(): array
    {
        $contexts = [];

        if ($userId = $this->tenantContext->getUserId()) {
            $contexts[] = ['user_id' => $userId];
        }

        if ($branchId = $this->tenantContext->getBranchId()) {
            $contexts[] = ['branch_id' => $branchId];
        }

        $company = $this->tenantContext->getCompany();
        while ($company) {
            $contexts[] = ['company_id' => $company->id];
            $company = $company->parent;
        }

        if ($workspaceId = $this->tenantContext->getWorkspaceId()) {
            $contexts[] = ['workspace_id' => $workspaceId];
        }

        $contexts[] = [];

        return $contexts;
    }
}
