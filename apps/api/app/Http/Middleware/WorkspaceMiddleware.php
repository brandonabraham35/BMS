<?php

namespace App\Http\Middleware;

use App\Domains\Organization\Tenant\TenantContext;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class WorkspaceMiddleware
{
    public function __construct(protected TenantContext $context) {}

    public function handle(Request $request, Closure $next): Response
    {
        if (!$this->context->hasWorkspace()) {
            return response()->json([
                'status' => 'Error',
                'message' => 'Unauthorized. No workspace context found.'
            ], 403);
        }

        return $next($request);
    }
}
