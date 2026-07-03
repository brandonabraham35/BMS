<?php

namespace App\Domains\AccessControl\Services;

use App\Models\User;
use App\Domains\Organization\Tenant\TenantContext;

class AuthorizationEngine
{
    public function __construct(
        protected PermissionResolver $permissionResolver,
        protected TenantContext $tenantContext
    ) {}

    public function check(User $user, string $permission, ?array $context = []): bool
    {
        // 1. Basic permission check
        if (!$user->hasPermission($permission)) {
            return false;
        }

        // 2. Tenant matching (Security Hardening)
        if ($user->workspace_id !== $this->tenantContext->getWorkspaceId()) {
            return false;
        }

        // 3. ABAC hooks (Future)
        // return $this->evaluateAbac($user, $permission, $context);

        return true;
    }
}
