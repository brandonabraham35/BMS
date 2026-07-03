<?php

namespace App\Domains\Organization\Controllers;

use App\Domains\Organization\Services\WorkspaceService;
use App\Http\Controllers\Controller;
use App\Core\BaseResource;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class WorkspaceController extends Controller
{
    use ApiResponse;

    public function __construct(protected WorkspaceService $workspaceService) {}

    public function index(Request $request): JsonResponse
    {
        $workspaces = $this->workspaceService->list($request);
        return $this->successResponse(BaseResource::collection($workspaces));
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:workspaces,slug',
            'status' => 'nullable|string|in:active,archived',
        ]);

        $workspace = $this->workspaceService->create($data);
        return $this->successResponse(new BaseResource($workspace), 'Workspace created successfully', 201);
    }

    public function show(\App\Models\Workspace $workspace): JsonResponse
    {
        $this->authorize('view', $workspace);
        return $this->successResponse(new BaseResource($workspace));
    }

    public function update(Request $request, \App\Models\Workspace $workspace): JsonResponse
    {
        $this->authorize('update', $workspace);
        $data = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'status' => 'nullable|string|in:active,archived',
        ]);

        $workspace = $this->workspaceService->update($workspace, $data);
        return $this->successResponse(new BaseResource($workspace), 'Workspace updated successfully');
    }

    public function destroy(\App\Models\Workspace $workspace): JsonResponse
    {
        $this->authorize('delete', $workspace);
        $this->workspaceService->delete($workspace);
        return $this->successResponse(null, 'Workspace archived successfully');
    }
}
