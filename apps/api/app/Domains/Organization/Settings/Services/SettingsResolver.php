<?php

namespace App\Domains\Organization\Settings\Services;

use App\Domains\Organization\Settings\Contracts\SettingsRepositoryInterface;
use App\Domains\Organization\Tenant\TenantContext;

class SettingsResolver
{
    public function __construct(
        protected SettingsRepositoryInterface $repository,
        protected TenantContext $tenantContext
    ) {}

    public function resolve(string $key, $default = null): mixed
    {
        // Hierarchy: User -> Dept -> Branch -> Company -> Workspace -> Platform
        $contexts = $this->getHierarchyContexts();

        foreach ($contexts as $context) {
            $setting = $this->repository->find($key, $context);
            if ($setting) {
                return $this->castValue($setting->value, $setting->type);
            }
        }

        return $default;
    }

    protected function getHierarchyContexts(): array
    {
        $contexts = [];

        // 1. User
        if ($this->tenantContext->getUserId()) {
            $contexts[] = ['user_id' => $this->tenantContext->getUserId()];
        }

        // 2. Department (Future)
        // if ($this->tenantContext->getDepartmentId()) { ... }

        // 3. Branch
        if ($this->tenantContext->getBranchId()) {
            $contexts[] = ['branch_id' => $this->tenantContext->getBranchId()];
        }

        // 4. Company
        if ($this->tenantContext->getCompanyId()) {
            $contexts[] = ['company_id' => $this->tenantContext->getCompanyId()];
        }

        // 5. Workspace
        if ($this->tenantContext->getWorkspaceId()) {
            $contexts[] = ['workspace_id' => $this->tenantContext->getWorkspaceId()];
        }

        // 6. Platform (all nulls)
        $contexts[] = [];

        return $contexts;
    }

    protected function castValue($value, string $type): mixed
    {
        return match ($type) {
            'boolean' => filter_var($value, FILTER_VALIDATE_BOOLEAN),
            'integer' => (int) $value,
            'float' => (float) $value,
            'json', 'array' => json_decode($value, true),
            default => $value,
        };
    }
}
