<?php

namespace App\Domains\Organization\Policies\Repositories;

use App\Domains\Organization\Policies\Contracts\PolicyRepositoryInterface;
use App\Models\OrganizationPolicy;

class DatabasePolicyRepository implements PolicyRepositoryInterface
{
    public function find(string $type, array $context): ?OrganizationPolicy
    {
        $query = OrganizationPolicy::where('type', $type);

        foreach (['workspace_id', 'company_id', 'branch_id'] as $field) {
            if (isset($context[$field])) {
                $query->where($field, $context[$field]);
            } else {
                $query->whereNull($field);
            }
        }

        return $query->where('is_active', true)->first();
    }

    public function updateOrCreate(string $type, string $name, array $rules, bool $isActive, array $context): OrganizationPolicy
    {
        $attributes = ['type' => $type];
        foreach (['workspace_id', 'company_id', 'branch_id'] as $field) {
            $attributes[$field] = $context[$field] ?? null;
        }

        return OrganizationPolicy::updateOrCreate(
            $attributes,
            ['name' => $name, 'rules' => $rules, 'is_active' => $isActive]
        );
    }
}
