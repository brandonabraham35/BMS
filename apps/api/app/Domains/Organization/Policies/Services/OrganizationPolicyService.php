<?php

namespace App\Domains\Organization\Policies\Services;

use App\Models\OrganizationPolicy;
use App\Services\AuditLogger;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class OrganizationPolicyService
{
    public function __construct(
        protected AuditLogger $auditLogger,
        protected PolicyValidator $validator
    ) {}

    public function list(Request $request): LengthAwarePaginator
    {
        return OrganizationPolicy::search($request, ['name', 'type'])
            ->where('company_id', $request->user()->company_id)
            ->paginate($request->input('per_page', 15));
    }

    public function create(array $data): OrganizationPolicy
    {
        $this->validator->validate($data['type'], $data['rules']);

        $policy = OrganizationPolicy::create($data);

        $this->auditLogger->log(
            'policy.created',
            $policy,
            null,
            $policy->toArray()
        );

        return $policy;
    }

    public function update(OrganizationPolicy $policy, array $data): OrganizationPolicy
    {
        if (isset($data['rules'])) {
            $this->validator->validate($policy->type, $data['rules']);
        }

        $oldValues = $policy->toArray();
        $policy->update($data);

        $this->auditLogger->log(
            'policy.updated',
            $policy,
            $oldValues,
            $policy->toArray()
        );

        return $policy;
    }

    public function delete(OrganizationPolicy $policy): void
    {
        $policy->delete();

        $this->auditLogger->log(
            'policy.deleted',
            $policy
        );
    }
}
