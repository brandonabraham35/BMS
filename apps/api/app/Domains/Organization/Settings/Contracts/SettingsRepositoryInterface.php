<?php

namespace App\Domains\Organization\Settings\Contracts;

use App\Models\Setting;

interface SettingsRepositoryInterface
{
    public function find(string $key, array $context): ?Setting;
    public function updateOrCreate(string $key, mixed $value, string $type, string $group, array $context): Setting;
}
