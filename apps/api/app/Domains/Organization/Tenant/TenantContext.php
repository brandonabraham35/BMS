<?php

namespace App\Domains\Organization\Tenant;

use App\Models\Branch;
use App\Models\Company;
use App\Models\User;
use App\Models\Workspace;

class TenantContext
{
    protected ?Workspace $workspace = null;
    protected ?Company $company = null;
    protected ?Branch $branch = null;
    protected ?User $user = null;

    public function setWorkspace(Workspace $workspace): void
    {
        $this->workspace = $workspace;
    }

    public function getWorkspace(): ?Workspace
    {
        return $this->workspace;
    }

    public function getWorkspaceId(): ?string
    {
        return $this->workspace?->id;
    }

    public function setCompany(Company $company): void
    {
        $this->company = $company;
    }

    public function getCompany(): ?Company
    {
        return $this->company;
    }

    public function getCompanyId(): ?string
    {
        return $this->company?->id;
    }

    public function setBranch(Branch $branch): void
    {
        $this->branch = $branch;
    }

    public function getBranch(): ?Branch
    {
        return $this->branch;
    }

    public function getBranchId(): ?string
    {
        return $this->branch?->id;
    }

    public function setUser(User $user): void
    {
        $this->user = $user;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function getUserId(): ?string
    {
        return $this->user?->id;
    }

    public function hasWorkspace(): bool
    {
        return !is_null($this->workspace);
    }

    public function hasCompany(): bool
    {
        return !is_null($this->company);
    }

    public function hasBranch(): bool
    {
        return !is_null($this->branch);
    }
}
