<?php

namespace App\Http\Middleware;

use App\Services\CompanyContext;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TenantMiddleware
{
    public function __construct(protected CompanyContext $context) {}

    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($user && $user->company_id) {
            $this->context->setCompany($user->company);

            if ($user->branch_id) {
                $this->context->setBranch($user->branch);
            }
        }

        // Future: Handle X-Company-ID header for multi-tenant switching if allowed

        return $next($request);
    }
}
