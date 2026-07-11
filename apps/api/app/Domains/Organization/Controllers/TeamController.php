<?php

namespace App\Domains\Organization\Controllers;

use App\Domains\Organization\Services\TeamService;
use App\Http\Controllers\Controller;
use App\Core\BaseResource;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TeamController extends Controller
{
    use ApiResponse;

    public function __construct(protected TeamService $teamService) {}

    public function index(Request $request): JsonResponse
    {
        $teams = $this->teamService->list($request);
        return $this->successResponse(BaseResource::collection($teams));
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'department_id' => 'required|exists:departments,id',
            'description' => 'nullable|string|max:500',
            'status' => 'nullable|string|in:active,inactive,archived',
        ]);

        $user = $request->user();
        $data['workspace_id'] = $user->workspace_id;
        $data['company_id'] = $user->company_id;
        $data['branch_id'] = $user->branch_id;

        $team = $this->teamService->create($data);
        return $this->successResponse(new BaseResource($team), 'Team created successfully', 201);
    }

    public function show(\App\Models\Team $team): JsonResponse
    {
        $this->authorize('view', $team);
        return $this->successResponse(new BaseResource($team));
    }

    public function update(Request $request, \App\Models\Team $team): JsonResponse
    {
        $this->authorize('update', $team);
        $data = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string|max:500',
            'status' => 'nullable|string|in:active,inactive,archived',
        ]);

        $team = $this->teamService->update($team, $data);
        return $this->successResponse(new BaseResource($team), 'Team updated successfully');
    }

    public function destroy(\App\Models\Team $team): JsonResponse
    {
        $this->authorize('delete', $team);
        $this->teamService->delete($team);
        return $this->successResponse(null, 'Team deleted successfully');
    }

    public function restore(string $id): JsonResponse
    {
        $team = \App\Models\Team::withTrashed()->findOrFail($id);
        $this->authorize('update', $team);

        $team = $this->teamService->restore($id);
        return $this->successResponse(new BaseResource($team), 'Team restored successfully');
    }

    public function forceDelete(string $id): JsonResponse
    {
        $team = \App\Models\Team::withTrashed()->findOrFail($id);
        $this->authorize('delete', $team);

        $team = $this->teamService->forceDelete($id);
        return $this->successResponse(null, 'Team permanently deleted');
    }
}
