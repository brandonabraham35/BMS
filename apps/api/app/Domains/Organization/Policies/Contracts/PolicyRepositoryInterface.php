<?php

namespace App\Domains\Organization\Policies\Contracts;

use App\Models\OrganizationPolicy;

interface PolicyRepositoryInterface
{
    public function find(string $type, array $context): ?OrganizationPolicy;
    public function updateOrCreate(string $type, string $name, array $rules, bool $isActive, array $context): OrganizationPolicy;
}
