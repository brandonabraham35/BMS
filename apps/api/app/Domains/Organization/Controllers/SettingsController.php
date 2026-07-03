<?php

namespace App\Domains\Organization\Controllers;

use App\Domains\Organization\Tenant\TenantContext;
use App\Http\Controllers\Controller;
use App\Services\SettingsService;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    use ApiResponse;

    public function __construct(
        protected SettingsService $settingsService,
        protected TenantContext $tenantContext
    ) {}

    /**
     * Get settings for the current context.
     */
    public function index(Request $request): JsonResponse
    {
        // Hierarchical retrieval is handled by the service/resolver
        $keys = $request->input('keys', []);
        $results = [];

        foreach ($keys as $key) {
            $results[$key] = $this->settingsService->get($key);
        }

        return $this->successResponse($results);
    }

    /**
     * Update settings for the current Workspace.
     */
    public function updateWorkspace(Request $request): JsonResponse
    {
        return $this->updateSettings($request, [
            'workspace_id' => $this->tenantContext->getWorkspaceId()
        ]);
    }

    /**
     * Update settings for the current Company.
     */
    public function updateCompany(Request $request): JsonResponse
    {
        return $this->updateSettings($request, [
            'company_id' => $this->tenantContext->getCompanyId()
        ]);
    }

    /**
     * Update settings for the current Branch.
     */
    public function updateBranch(Request $request): JsonResponse
    {
        return $this->updateSettings($request, [
            'branch_id' => $this->tenantContext->getBranchId()
        ]);
    }

    protected function updateSettings(Request $request, array $context): JsonResponse
    {
        $settings = $request->validate([
            'settings' => 'required|array',
            'settings.*.key' => 'required|string',
            'settings.*.value' => 'required',
            'settings.*.type' => 'required|string|in:string,boolean,integer,float,json,array',
            'settings.*.group' => 'sometimes|string',
        ]);

        foreach ($settings['settings'] as $item) {
            $this->settingsService->set(
                key: $item['key'],
                value: $item['value'],
                context: $context,
                type: $item['type'],
                group: $item['group'] ?? 'general'
            );
        }

        return $this->successResponse(null, 'Settings updated successfully');
    }
}
