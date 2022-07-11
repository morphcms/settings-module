<?php

namespace Modules\Settings\Observers;

use Modules\Settings\Services\SettingsService;
use OptimistDigital\NovaSettings\Models\Settings;

class SettingsObserver
{
    public function saved(Settings $settings)
    {
        if ($settings->isDirty()) {
            app(SettingsService::class)->syncWithEnv($settings->key, $settings->value);
        }
    }
}
