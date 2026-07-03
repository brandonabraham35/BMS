<?php

namespace App\Domains\Organization\Controllers;

use App\Domains\Organization\Services\CompanyService;
use App\Http\Controllers\Controller;
use App\Core\BaseResource;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    use ApiResponse;

    public function __construct(protected CompanyService $companyService) {}

    public function index(Request $request): JsonResponse
    {
        $companies = $this->companyService->list($request);
        return $this->successResponse(BaseResource::collection($companies));
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'legal_name' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:50',
            'country' => 'nullable|string|max:100',
            'currency' => 'nullable|string|size:3',
            'timezone' => 'nullable|string|max:100',
            'status' => 'nullable|string|in:active,inactive,archived',
        ]);

        $data['workspace_id'] = $request->user()->workspace_id;

        $company = $this->companyService->create($data);
        return $this->successResponse(new BaseResource($company), 'Company created successfully', 201);
    }

    public function show(\App\Models\Company $company): JsonResponse
    {
        $this->authorize('view', $company);
        return $this->successResponse(new BaseResource($company));
    }

    public function update(Request $request, \App\Models\Company $company): JsonResponse
    {
        $this->authorize('update', $company);

        $data = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'legal_name' => 'nullable|string|max:255',
            'status' => 'nullable|string|in:active,inactive,archived',
        ]);

        $company = $this->companyService->update($company, $data);
        return $this->successResponse(new BaseResource($company), 'Company updated successfully');
    }

    public function destroy(\App\Models\Company $company): JsonResponse
    {
        $this->authorize('delete', $company);
        $this->companyService->delete($company);
        return $this->successResponse(null, 'Company deleted successfully');
    }
}
