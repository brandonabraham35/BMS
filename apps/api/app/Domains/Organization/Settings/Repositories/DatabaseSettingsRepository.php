<?php

namespace App\Domains\Organization\Settings\Repositories;

use App\Domains\Organization\Settings\Contracts\SettingsRepositoryInterface;
use App\Models\Setting;

class DatabaseSettingsRepository implements SettingsRepositoryInterface
{
    public function find(string $key, array $context): ?Setting
    {
        $query = Setting::where('key', $key);

        foreach (['workspace_id', 'company_id', 'branch_id', 'department_id', 'user_id'] as $field) {
            if (isset($context[$field])) {
                $query->where($field, $context[$field]);
            } else {
                $query->whereNull($field);
            }
        }

        return $query->first();
    }

    public function updateOrCreate(string $key, mixed $value, string $type, string $group, array $context): Setting
    {
        $attributes = ['key' => $key];
        foreach (['workspace_id', 'company_id', 'branch_id', 'department_id', 'user_id'] as $field) {
            $attributes[$field] = $context[$field] ?? null;
        }

        return Setting::updateOrCreate(
            $attributes,
            [
                'value' => $this->formatValue($value, $type),
                'type' => $type,
                'group' => $group
            ]
        );
    }

    protected function formatValue(mixed $value, string $type): string
    {
        if ($type === 'json' || $type === 'array') {
            return json_encode($value);
        }

        return (string) $value;
    }
}
