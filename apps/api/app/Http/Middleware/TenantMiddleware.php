<?php

namespace App\Http\Middleware;

use App\Domains\Organization\Tenant\TenantContext;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TenantMiddleware
{
    public function __construct(
        protected TenantContext $context
    ) {}

    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($user) {
            $this->context->setUser($user);

            if ($user->workspace_id) {
               $workspace = \App\Models\Workspace::find($user->workspace_id);
               if ($workspace) {
                   $this->context->setWorkspace($workspace);
               }
            }

            if ($user->company_id) {
               $company = \App\Models\Company::find($user->company_id);
               if ($company) {
                   $this->context->setCompany($company);
               }

               if ($user->branch_id) {
                   $branch = \App\Models\Branch::find($user->branch_id);
                   if ($branch) {
                       $this->context->setBranch($branch);
                   }
               }
            }
        }

        app()->instance(TenantContext::class, $this->context);

        return $next($request);
    }
}
