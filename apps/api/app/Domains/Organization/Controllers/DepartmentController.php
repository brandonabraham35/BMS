<?php

namespace App\Domains\Organization\Controllers;

use App\Domains\Organization\Services\DepartmentService;
use App\Http\Controllers\Controller;
use App\Core\BaseResource;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    use ApiResponse;

    public function __construct(protected DepartmentService $departmentService) {}

    public function index(Request $request): JsonResponse
    {
        $departments = $this->departmentService->list($request);
        return $this->successResponse(BaseResource::collection($departments));
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'nullable|string|max:50',
            'parent_id' => 'nullable|exists:departments,id',
            'is_active' => 'boolean',
        ]);

        $user = $request->user();
        $data['workspace_id'] = $user->workspace_id;
        $data['company_id'] = $user->company_id;
        $data['branch_id'] = $user->branch_id;

        $department = $this->departmentService->create($data);
        return $this->successResponse(new BaseResource($department), 'Department created successfully', 201);
    }

    public function show(\App\Models\Department $department): JsonResponse
    {
        $this->authorize('view', $department);
        return $this->successResponse(new BaseResource($department));
    }

    public function update(Request $request, \App\Models\Department $department): JsonResponse
    {
        $this->authorize('update', $department);
        $data = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'code' => 'nullable|string|max:50',
            'is_active' => 'boolean',
        ]);

        $department = $this->departmentService->update($department, $data);
        return $this->successResponse(new BaseResource($department), 'Department updated successfully');
    }

    public function destroy(\App\Models\Department $department): JsonResponse
    {
        $this->authorize('delete', $department);
        $this->departmentService->delete($department);
        return $this->successResponse(null, 'Department deleted successfully');
    }
}
