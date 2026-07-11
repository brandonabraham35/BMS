<?php

namespace App\Domains\Organization\Services;

use App\Models\User;
use App\Models\UserTransfer;
use App\Services\AuditLogger;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TransferService
{
    public function __construct(protected AuditLogger $auditLogger) {}

    public function transfer(User $user, array $destination, string $reason = null): UserTransfer
    {
        return DB::transaction(function () use ($user, $destination, $reason) {
            $previousState = $user->only([
                'workspace_id',
                'company_id',
                'branch_id',
                'department_id',
            ]);

            $transfer = UserTransfer::create([
                'user_id' => $user->id,
                'from_workspace_id' => $user->workspace_id,
                'from_company_id' => $user->company_id,
                'from_branch_id' => $user->branch_id,
                'from_department_id' => $user->department_id,
                'to_workspace_id' => $destination['workspace_id'] ?? $user->workspace_id,
                'to_company_id' => $destination['company_id'] ?? null,
                'to_branch_id' => $destination['branch_id'] ?? null,
                'to_department_id' => $destination['department_id'] ?? null,
                'reason' => $reason,
                'transferred_by' => Auth::id(),
                'created_by' => Auth::id(), // Match legacy column requirement
                'transferred_at' => now(),
                'effective_at' => now(), // Match legacy column requirement
                'previous_state' => $previousState,
            ]);

            $user->update([
                'workspace_id' => $transfer->to_workspace_id,
                'company_id' => $transfer->to_company_id,
                'branch_id' => $transfer->to_branch_id,
                'department_id' => $transfer->to_department_id,
            ]);

            $transfer->update(['new_state' => $user->only([
                'workspace_id',
                'company_id',
                'branch_id',
                'department_id',
            ])]);

            $this->auditLogger->log(
                'user.transferred',
                $user,
                $previousState,
                $transfer->new_state
            );

            return $transfer;
        });
    }

    public function getHistory(User $user)
    {
        return UserTransfer::where('user_id', $user->id)
            ->orderBy('transferred_at', 'desc')
            ->get();
    }
}
