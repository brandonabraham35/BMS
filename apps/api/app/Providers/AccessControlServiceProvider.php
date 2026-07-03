<?php

namespace App\Providers;

use App\Domains\AccessControl\Services\PermissionResolver;
use App\Domains\AccessControl\Services\AuthorizationEngine;
use Illuminate\Support\ServiceProvider;

class AccessControlServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(PermissionResolver::class);
        $this->app->singleton(AuthorizationEngine::class);
    }

    public function boot(): void
    {
        //
    }
}
