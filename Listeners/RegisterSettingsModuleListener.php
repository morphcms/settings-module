<?php

namespace Modules\Settings\Listeners;

use Outl1ne\NovaSettings\NovaSettings;

class RegisterSettingsModuleListener
{
    public function __invoke($event): array
    {
        return [
            NovaSettings::make(),
        ];
    }

}
