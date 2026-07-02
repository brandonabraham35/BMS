<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\ModuleRegistry;

class IdentityServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(ModuleRegistry $registry): void
    {
        $registry->register([
            'name' => 'Identity',
            'version' => '1.0.0',
            'description' => 'Core Identity and Access Management',
            'enabled' => true,
            'submodules' => [
                'Authentication',
                'Sessions',
                'Devices',
                'Passwords',
                'Invitations',
                'Verification'
            ]
        ]);
    }
}
