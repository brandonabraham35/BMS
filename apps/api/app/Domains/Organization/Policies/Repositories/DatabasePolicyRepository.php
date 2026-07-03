<?php

namespace App\Domains\Organization\Policies\Repositories;

use App\Domains\Organization\Policies\Contracts\PolicyRepositoryInterface;
use App\Models\OrganizationPolicy;

class DatabasePolicyRepository implements PolicyRepositoryInterface
{
    public function find(string $type, array $context): ?OrganizationPolicy
    {
        $query = OrganizationPolicy::where('type', $type);

        if (isset($context['company_id'])) {
            $query->where('company_id', $context['company_id']);
        } else {
            $query->whereNull('company_id');
        }

        return $query->where('is_active', true)->first();
    }

    public function updateOrCreate(string $type, string $name, array $rules, bool $isActive, array $context): OrganizationPolicy
    {
        return OrganizationPolicy::updateOrCreate(
            ['type' => $type, 'company_id' => $context['company_id'] ?? null],
            ['name' => $name, 'rules' => $rules, 'is_active' => $isActive]
        );
    }
}
