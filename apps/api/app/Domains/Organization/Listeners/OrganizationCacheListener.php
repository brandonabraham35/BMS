<?php

namespace App\Domains\Organization\Listeners;

use App\Domains\Organization\Caching\Contracts\CacheInvalidatorInterface;
use App\Domains\Organization\Events\WorkspaceUpdated;
use App\Domains\Organization\Events\CompanyUpdated;
use App\Domains\Organization\Events\BranchUpdated;
use App\Domains\Organization\Events\SettingsChanged;
use App\Domains\Organization\Events\PolicyChanged;

class OrganizationCacheListener
{
    public function __construct(protected CacheInvalidatorInterface $invalidator) {}

    public function handleWorkspaceUpdated(WorkspaceUpdated $event): void
    {
        $this->invalidator->invalidateWorkspace($event->workspaceId);
    }

    public function handleCompanyUpdated(CompanyUpdated $event): void
    {
        $this->invalidator->invalidateCompany($event->companyId);
    }

    public function handleBranchUpdated(BranchUpdated $event): void
    {
        $this->invalidator->invalidateBranch($event->branchId);
    }

    public function handleSettingsChanged(SettingsChanged $event): void
    {
        // Invalidate specific context or everything if needed
    }

    public function handlePolicyChanged(PolicyChanged $event): void
    {
        // Invalidate specific context
    }

    public function subscribe($events): array
    {
        return [
            WorkspaceUpdated::class => 'handleWorkspaceUpdated',
            CompanyUpdated::class => 'handleCompanyUpdated',
            BranchUpdated::class => 'handleBranchUpdated',
            SettingsChanged::class => 'handleSettingsChanged',
            PolicyChanged::class => 'handlePolicyChanged',
        ];
    }
}
