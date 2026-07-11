<?php

namespace App\Domains\Organization\Caching\Contracts;

interface CacheInvalidatorInterface
{
    public function invalidateWorkspace(string $workspaceId): void;
    public function invalidateCompany(string $companyId): void;
    public function invalidateBranch(string $branchId): void;
    public function invalidateAll(): void;
}
