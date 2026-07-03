<?php

namespace App\Domains\Organization\Services;

use App\Models\Team;
use App\Services\AuditLogger;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class TeamService
{
    public function __construct(protected AuditLogger $auditLogger) {}

    public function list(Request $request): LengthAwarePaginator
    {
        $query = Team::search($request, ['name']);

        // Ensure user can only list teams in their branch/department boundaries
        $query->where('branch_id', $request->user()->branch_id);

        if ($request->filled('department_id')) {
            $query->where('department_id', $request->input('department_id'));
        }

        return $query->paginate($request->input('per_page', 15));
    }

    public function create(array $data): Team
    {
        $team = Team::create($data);

        $this->auditLogger->log(
            'team.created',
            $team,
            null,
            $team->toArray()
        );

        return $team;
    }

    public function update(Team $team, array $data): Team
    {
        $oldValues = $team->toArray();
        $team->update($data);

        $this->auditLogger->log(
            'team.updated',
            $team,
            $oldValues,
            $team->toArray()
        );

        return $team;
    }

    public function delete(Team $team): void
    {
        $team->delete();

        $this->auditLogger->log(
            'team.deleted',
            $team
        );
    }
}
