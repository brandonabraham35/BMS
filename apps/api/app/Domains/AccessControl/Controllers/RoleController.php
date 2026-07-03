<?php

namespace App\Domains\AccessControl\Controllers;

use App\Domains\AccessControl\Services\RoleService;
use App\Http\Controllers\Controller;
use App\Core\BaseResource;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    use ApiResponse;

    public function __construct(protected RoleService $roleService) {}

    public function index(Request $request): JsonResponse
    {
        $roles = $this->roleService->list($request);
        return $this->successResponse(BaseResource::collection($roles));
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,id',
        ]);

        $data['workspace_id'] = $request->user()->workspace_id;
        $data['company_id'] = $request->user()->company_id;

        $role = $this->roleService->create($data);
        return $this->successResponse(new BaseResource($role), 'Role created successfully', 201);
    }

    public function show(\App\Models\Role $role): JsonResponse
    {
        $this->authorize('view', $role);
        $role->load('permissions');
        return $this->successResponse(new BaseResource($role));
    }

    public function update(Request $request, \App\Models\Role $role): JsonResponse
    {
        $this->authorize('update', $role);
        $data = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'permissions' => 'nullable|array',
        ]);

        $role = $this->roleService->update($role, $data);
        return $this->successResponse(new BaseResource($role), 'Role updated successfully');
    }

    public function clone(Request $request, \App\Models\Role $role): JsonResponse
    {
        $this->authorize('update', $role);
        $data = $request->validate(['name' => 'required|string|max:255']);

        $newRole = $this->roleService->clone($role, $data['name']);
        return $this->successResponse(new BaseResource($newRole), 'Role cloned successfully', 201);
    }
}
