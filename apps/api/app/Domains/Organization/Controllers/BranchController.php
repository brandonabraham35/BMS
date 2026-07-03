<?php

namespace App\Domains\Organization\Controllers;

use App\Domains\Organization\Services\BranchService;
use App\Http\Controllers\Controller;
use App\Core\BaseResource;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BranchController extends Controller
{
    use ApiResponse;

    public function __construct(protected BranchService $branchService) {}

    public function index(Request $request): JsonResponse
    {
        $branches = $this->branchService->list($request);
        return $this->successResponse(BaseResource::collection($branches));
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:50',
            'address' => 'nullable|string',
            'status' => 'nullable|string|in:active,inactive,archived',
        ]);

        $data['company_id'] = $request->user()->company_id;

        $branch = $this->branchService->create($data);
        return $this->successResponse(new BaseResource($branch), 'Branch created successfully', 201);
    }

    public function show(\App\Models\Branch $branch): JsonResponse
    {
        $this->authorize('view', $branch);
        return $this->successResponse(new BaseResource($branch));
    }

    public function update(Request $request, \App\Models\Branch $branch): JsonResponse
    {
        $this->authorize('update', $branch);

        $data = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'code' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:255',
            'status' => 'nullable|string|in:active,inactive,archived',
        ]);

        $branch = $this->branchService->update($branch, $data);
        return $this->successResponse(new BaseResource($branch), 'Branch updated successfully');
    }

    public function destroy(\App\Models\Branch $branch): JsonResponse
    {
        $this->authorize('delete', $branch);
        $this->branchService->delete($branch);
        return $this->successResponse(null, 'Branch deleted successfully');
    }
}
