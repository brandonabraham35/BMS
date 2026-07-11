<?php

namespace App\Domains\Organization\Services;

use App\Models\Branch;
use App\Services\AuditLogger;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Domains\Organization\Events\BranchUpdated;

class BranchService
{
    public function __construct(protected AuditLogger $auditLogger) {}

    public function list(Request $request): LengthAwarePaginator
    {
        $query = Branch::search($request, ['name', 'code', 'email'])
            ->where('company_id', $request->user()->company_id);

        if ($request->boolean('with_archived')) {
            $query->withTrashed();
        }

        return $query->paginate($request->input('per_page', 15));
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

        event(new BranchUpdated($branch->id));

        return $branch;
    }

    public function delete(Branch $branch): void
    {
        $branch->delete();

        $this->auditLogger->log(
            'branch.deleted',
            $branch
        );

        event(new BranchUpdated($branch->id));
    }

    public function restore(string $id): Branch
    {
        $branch = Branch::withTrashed()->findOrFail($id);
        $branch->restore();

        $this->auditLogger->log(
            'branch.restored',
            $branch
        );

        event(new BranchUpdated($branch->id));

        return $branch;
    }

    public function forceDelete(string $id): void
    {
        $branch = Branch::withTrashed()->findOrFail($id);

        $this->auditLogger->log(
            'branch.permanently_deleted',
            $branch
        );

        $branch->forceDelete();
    }
}
