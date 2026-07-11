<?php

namespace App\Domains\Organization\Caching\Services;

use App\Domains\Organization\Caching\Contracts\CacheInvalidatorInterface;
use Illuminate\Contracts\Cache\Repository as CacheRepository;

class OrganizationCacheInvalidator implements CacheInvalidatorInterface
{
    public function __construct(protected CacheRepository $cache) {}

    public function invalidateWorkspace(string $workspaceId): void
    {
        $this->cache->forget("settings:workspace:{$workspaceId}");
        $this->cache->forget("policies:workspace:{$workspaceId}");
        $this->cache->forget("settings:workspace:{$workspaceId}:general"); // Common key
    }

    public function invalidateCompany(string $companyId): void
    {
        $this->cache->forget("settings:company:{$companyId}");
        $this->cache->forget("policies:company:{$companyId}");
        $this->cache->forget("settings:company:{$companyId}:general");
    }

    public function invalidateBranch(string $branchId): void
    {
        $this->cache->forget("settings:branch:{$branchId}");
        $this->cache->forget("policies:branch:{$branchId}");
        $this->cache->forget("settings:branch:{$branchId}:general");
    }

    public function invalidateAll(): void
    {
        // Dangerous in production if shared cache, but good for testing
    }
}
