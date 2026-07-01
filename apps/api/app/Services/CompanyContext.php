<?php

namespace App\Services;

use App\Models\Branch;
use App\Models\Company;

class CompanyContext
{
    protected ?Company $company = null;

    protected ?Branch $branch = null;

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
}
