<?php

namespace App\Domains\Organization\Controllers;

use App\Domains\Organization\Policies\Services\OrganizationPolicyService;
use App\Http\Controllers\Controller;
use App\Core\BaseResource;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class OrganizationPolicyController extends Controller
{
    use ApiResponse;

    public function __construct(protected OrganizationPolicyService $policyService) {}

    public function index(Request $request): JsonResponse
    {
        $policies = $this->policyService->list($request);
        return $this->successResponse(BaseResource::collection($policies));
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|string|max:100',
            'rules' => 'required|array',
            'is_active' => 'boolean',
            'company_id' => 'nullable|exists:companies,id',
            'branch_id' => 'nullable|exists:branches,id',
        ]);

        $user = $request->user();
        $data['workspace_id'] = $user->workspace_id;

        // Auto-assign company/branch if in context and not specified
        if (!isset($data['company_id']) && $user->company_id) {
            $data['company_id'] = $user->company_id;
        }

        $policy = $this->policyService->create($data);
        return $this->successResponse(new BaseResource($policy), 'Policy created successfully', 201);
    }

    public function show(\App\Models\OrganizationPolicy $policy): JsonResponse
    {
        $this->authorize('view', $policy);
        return $this->successResponse(new BaseResource($policy));
    }

    public function update(Request $request, \App\Models\OrganizationPolicy $policy): JsonResponse
    {
        $this->authorize('update', $policy);
        $data = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'rules' => 'sometimes|required|array',
            'is_active' => 'boolean',
        ]);

        $policy = $this->policyService->update($policy, $data);
        return $this->successResponse(new BaseResource($policy), 'Policy updated successfully');
    }

    public function destroy(\App\Models\OrganizationPolicy $policy): JsonResponse
    {
        $this->authorize('delete', $policy);
        $this->policyService->delete($policy);
        return $this->successResponse(null, 'Policy deleted successfully');
    }
}
