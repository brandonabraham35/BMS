<?php

namespace App\Domains\AccessControl\Controllers;

use App\Models\Permission;
use App\Models\PermissionGroup;
use App\Models\PermissionCategory;
use App\Http\Controllers\Controller;
use App\Core\BaseResource;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PermissionController extends Controller
{
    use ApiResponse;

    public function index(Request $request): JsonResponse
    {
        $permissions = Permission::search($request, ['name', 'slug'])
            ->with('permissionGroup.category')
            ->paginate($request->input('per_page', 50));

        return $this->successResponse(BaseResource::collection($permissions));
    }

    public function categories(): JsonResponse
    {
        return $this->successResponse(BaseResource::collection(PermissionCategory::with('groups.permissions')->get()));
    }

    public function groups(): JsonResponse
    {
        return $this->successResponse(BaseResource::collection(PermissionGroup::with('permissions')->get()));
    }
}
