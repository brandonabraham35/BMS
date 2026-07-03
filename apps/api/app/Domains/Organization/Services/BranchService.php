<?php

namespace App\Domains\Organization\Services;

use App\Models\Branch;
use App\Services\AuditLogger;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class BranchService
{
    public function __construct(protected AuditLogger $auditLogger) {}

    public function list(Request $request): LengthAwarePaginator
    {
        return Branch::search($request, ['name', 'code', 'email'])
            ->where('company_id', $request->user()->company_id)
            ->paginate($request->input('per_page', 15));
    }

    public function create(array $data): Branch
    {
        $branch = Branch::create($data);

        $this->auditLogger->log(
            'branch.created',
            $branch,
            null,
            $branch->toArray()
        );

        return $branch;
    }

    public function update(Branch $branch, array $data): Branch
    {
        $oldValues = $branch->toArray();
        $branch->update($data);

        $this->auditLogger->log(
            'branch.updated',
            $branch,
            $oldValues,
            $branch->toArray()
        );

        return $branch;
    }

    public function delete(Branch $branch): void
    {
        $branch->delete();

        $this->auditLogger->log(
            'branch.deleted',
            $branch
        );
    }
}
