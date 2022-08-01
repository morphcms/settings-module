<?php

namespace Modules\Settings\Listeners;

use Modules\Settings\Events\BootSettingsPage;
use Modules\Settings\Pages\MailSettings;

class RegisterPagesListener
{
    public function __invoke(BootSettingsPage $event): array
    {
        return [
            new MailSettings(),
        ];
    }
}
