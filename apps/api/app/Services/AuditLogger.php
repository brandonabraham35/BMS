<?php

namespace App\Services;

use App\Models\AuditLog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuditLogger
{
    public function __construct(
        protected CompanyContext $context,
        protected Request $request
    ) {}

    public function log(string $action, Model $entity, ?array $oldValues = null, ?array $newValues = null): void
    {
        AuditLog::create([
            'company_id' => $this->context->getCompanyId(),
            'user_id' => Auth::id(),
            'action' => $action,
            'entity_type' => get_class($entity),
            'entity_id' => $entity->getKey(),
            'old_values' => $oldValues,
            'new_values' => $newValues,
            'ip_address' => $this->request->ip(),
            'user_agent' => $this->request->userAgent(),
        ]);
    }
}
