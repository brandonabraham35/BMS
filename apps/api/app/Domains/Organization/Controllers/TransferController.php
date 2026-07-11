<?php

namespace App\Domains\Organization\Controllers;

use App\Domains\Organization\Services\TransferService;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Core\BaseResource;
use Illuminate\Support\Facades\Gate;

class TransferController extends Controller
{
    use ApiResponse;

    public function __construct(protected TransferService $transferService) {}

    public function store(Request $request, User $user): JsonResponse
    {
        $this->authorize('update', $user);

        $data = $request->validate([
            'workspace_id' => 'sometimes|uuid|exists:workspaces,id',
            'company_id' => 'sometimes|uuid|exists:companies,id',
            'branch_id' => 'sometimes|uuid|exists:branches,id',
            'department_id' => 'sometimes|uuid|exists:departments,id',
            'reason' => 'nullable|string|max:255',
        ]);

        $transfer = $this->transferService->transfer($user, $data, $data['reason'] ?? null);

        return $this->successResponse(new BaseResource($transfer), 'User transferred successfully');
    }

    public function index(User $user): JsonResponse
    {
        $this->authorize('view', $user);

        $history = $this->transferService->getHistory($user);
        return $this->successResponse(BaseResource::collection($history));
    }
}
