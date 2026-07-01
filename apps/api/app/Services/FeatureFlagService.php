<?php

namespace App\Services;

class FeatureFlagService
{
    public function __construct(protected SettingsService $settings) {}

    public function isEnabled(string $feature, ?string $companyId = null): bool
    {
        return $this->settings->get("feature.$feature", false, $companyId);
    }
}
