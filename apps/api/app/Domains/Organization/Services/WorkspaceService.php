<?php

namespace App\Domains\Organization\Services;

use App\Models\Workspace;
use App\Services\AuditLogger;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Str;

class WorkspaceService
{
    public function __construct(protected AuditLogger $auditLogger) {}

    public function list(Request $request): LengthAwarePaginator
    {
        return Workspace::search($request, ['name', 'slug'])
            ->paginate($request->input('per_page', 15));
    }

    public function create(array $data): Workspace
    {
        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['name']);
        }

        $workspace = Workspace::create($data);

        $this->auditLogger->log(
            action: 'workspace.created',
            entityType: Workspace::class,
            entityId: $workspace->id,
            newValues: $workspace->toArray()
        );

        return $workspace;
    }

    public function update(Workspace $workspace, array $data): Workspace
    {
        $oldValues = $workspace->toArray();
        $workspace->update($data);

        $this->auditLogger->log(
            action: 'workspace.updated',
            entityType: Workspace::class,
            entityId: $workspace->id,
            oldValues: $oldValues,
            newValues: $workspace->toArray()
        );

        return $workspace;
    }

    public function delete(Workspace $workspace): void
    {
        $workspace->delete();

        $this->auditLogger->log(
            action: 'workspace.archived',
            entityType: Workspace::class,
            entityId: $workspace->id
        );
    }
}
