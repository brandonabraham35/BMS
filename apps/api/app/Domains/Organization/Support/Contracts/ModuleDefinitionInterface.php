<?php

namespace App\Domains\Organization\Support\Contracts;

interface ModuleDefinitionInterface
{
    public function getName(): string;
    public function getDefaultSettings(): array;
    public function getDefaultPolicies(): array;
    public function getConfigurationSchema(): array;
    public function getValidationRules(): array;
    public function getCacheNamespaces(): array;
}
