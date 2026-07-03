<?php

namespace App\Services;

use App\Domains\Organization\Settings\Contracts\SettingsRepositoryInterface;
use App\Domains\Organization\Settings\Services\SettingsResolver;
use App\Models\Setting;

class SettingsService
{
    public function __construct(
        protected SettingsRepositoryInterface $repository,
        protected SettingsResolver $resolver,
        protected AuditLogger $auditLogger
    ) {}

    public function get(string $key, $default = null): mixed
    {
        return $this->resolver->resolve($key, $default);
    }

    public function set(string $key, $value, array $context = [], string $type = 'string', string $group = 'general'): Setting
    {
        $oldSetting = $this->repository->find($key, $context);
        $oldValues = $oldSetting ? $oldSetting->toArray() : null;

        $setting = $this->repository->updateOrCreate($key, $value, $type, $group, $context);

        $this->auditLogger->log(
            'setting.updated',
            $setting,
            $oldValues,
            $setting->toArray()
        );

        return $setting;
    }
}
