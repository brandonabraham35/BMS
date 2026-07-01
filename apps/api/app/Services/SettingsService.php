<?php

namespace App\Services;

use App\Models\Setting;

class SettingsService
{
    public function get(string $key, $default = null, ?string $companyId = null, ?string $branchId = null)
    {
        $setting = Setting::where('key', $key)
            ->where('company_id', $companyId)
            ->where('branch_id', $branchId)
            ->first();

        return $setting ? $this->castValue($setting->value, $setting->type) : $default;
    }

    public function set(string $key, $value, string $type = 'string', ?string $companyId = null, ?string $branchId = null): Setting
    {
        return Setting::updateOrCreate(
            ['key' => $key, 'company_id' => $companyId, 'branch_id' => $branchId],
            ['value' => (string) $value, 'type' => $type]
        );
    }

    protected function castValue($value, string $type)
    {
        return match ($type) {
            'boolean' => filter_var($value, FILTER_VALIDATE_BOOLEAN),
            'integer' => (int) $value,
            'float' => (float) $value,
            'json' => json_decode($value, true),
            default => $value,
        };
    }
}
