<?php

namespace App\Services;

class ModuleRegistry
{
    protected array $modules = [];

    public function register(array $module): void
    {
        $this->modules[$module['name']] = $module;
    }

    public function getModules(): array
    {
        return $this->modules;
    }

    public function getEnabledModules(): array
    {
        return array_filter($this->modules, fn ($m) => $m['enabled'] ?? true);
    }
}
