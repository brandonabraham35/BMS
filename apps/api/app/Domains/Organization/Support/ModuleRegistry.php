<?php

namespace App\Domains\Organization\Support;

use App\Domains\Organization\Support\Contracts\ModuleDefinitionInterface;

class ModuleRegistry
{
    protected array $modules = [];

    public function register(ModuleDefinitionInterface $module): void
    {
        $this->modules[$module->getName()] = $module;
    }

    public function getModules(): array
    {
        return $this->modules;
    }

    public function getDefaultSettings(): array
    {
        $settings = [];
        foreach ($this->modules as $module) {
            $settings = array_merge($settings, $module->getDefaultSettings());
        }
        return $settings;
    }

    public function getDefaultPolicies(): array
    {
        $policies = [];
        foreach ($this->modules as $module) {
            $policies = array_merge($policies, $module->getDefaultPolicies());
        }
        return $policies;
    }
}
