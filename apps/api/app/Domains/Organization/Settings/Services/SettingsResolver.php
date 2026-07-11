<?php

namespace App\Domains\Organization\Settings\Services;

use App\Domains\Organization\Settings\Contracts\SettingsRepositoryInterface;
use App\Domains\Organization\Tenant\TenantContext;
use App\Domains\Organization\Caching\Contracts\SettingsCacheInterface;

class SettingsResolver
{
    public function __construct(
        protected SettingsRepositoryInterface $repository,
        protected TenantContext $tenantContext,
        protected SettingsCacheInterface $cache
    ) {}

    public function resolve(string $key, $default = null): mixed
    {
        $contexts = $this->getHierarchyContexts();

        foreach ($contexts as $context) {
            $cached = $this->cache->get($key, $context);
            if ($cached !== null) {
                return $cached;
            }

            $setting = $this->repository->find($key, $context);
            if ($setting) {
                $value = $this->castValue($setting->value, $setting->type);
                $this->cache->put($key, $value, $context);
                return $value;
            }
        }

        return $default;
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
