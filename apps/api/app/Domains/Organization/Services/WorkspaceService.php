<?php

namespace App\Domains\Organization\Services;

use App\Models\Workspace;
use App\Services\AuditLogger;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Str;
use App\Domains\Organization\Events\WorkspaceUpdated;

class WorkspaceService
{
    public function __construct(protected AuditLogger $auditLogger) {}

    public function list(Request $request): LengthAwarePaginator
    {
        $query = Workspace::search($request, ['name', 'slug']);

        if ($request->boolean('with_archived')) {
            $query->withTrashed();
        }

        return $query->paginate($request->input('per_page', 15));
    }

    public function create(array $data): Workspace
    {
        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['name']);
        }

        $workspace = Workspace::create($data);

        $this->auditLogger->log(
            'workspace.created',
            $workspace,
            null,
            $workspace->toArray()
        );

        return $workspace;
    }

    public function update(Workspace $workspace, array $data): Workspace
    {
        $oldValues = $workspace->toArray();
        $workspace->update($data);

        $this->auditLogger->log(
            'workspace.updated',
            $workspace,
            $oldValues,
            $workspace->toArray()
        );

        event(new WorkspaceUpdated($workspace->id));

        return $workspace;
    }

    public function delete(Workspace $workspace): void
    {
        $workspace->delete();

        $this->auditLogger->log(
            'workspace.archived',
            $workspace
        );

        event(new WorkspaceUpdated($workspace->id));
    }

    public function restore(string $id): Workspace
    {
        $workspace = Workspace::withTrashed()->findOrFail($id);
        $workspace->restore();

        $this->auditLogger->log(
            'workspace.restored',
            $workspace
        );

        event(new WorkspaceUpdated($workspace->id));

        return $workspace;
    }

    public function forceDelete(string $id): void
    {
        $workspace = Workspace::withTrashed()->findOrFail($id);

        $this->auditLogger->log(
            'workspace.permanently_deleted',
            $workspace
        );

        $workspace->forceDelete();
    }
}
