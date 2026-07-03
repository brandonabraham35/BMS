<?php

namespace App\Providers;

use App\Domains\Organization\Tenant\TenantContext;
use App\Domains\Organization\Tenant\TenantResolver;
use App\Services\CompanyContext;
use Illuminate\Support\ServiceProvider;

class OrganizationServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(TenantContext::class, function ($app) {
            return new TenantContext();
        });

        // Maintain backward compatibility for now
        $this->app->alias(TenantContext::class, CompanyContext::class);

        $this->app->singleton(TenantResolver::class, function ($app) {
            return new TenantResolver($app->make(TenantContext::class));
        });

        $this->app->bind(
            \App\Domains\Organization\Settings\Contracts\SettingsRepositoryInterface::class,
            \App\Domains\Organization\Settings\Repositories\DatabaseSettingsRepository::class
        );

        $this->app->singleton(\App\Domains\Organization\Settings\Services\SettingsResolver::class);
    }

    public function boot(): void
    {
        //
    }
}
