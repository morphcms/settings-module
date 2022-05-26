<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInite21157ee29f4a3941834efcbf8101477
{
    public static $prefixLengthsPsr4 = array (
        'M' => 
        array (
            'Modules\\Settings\\' => 17,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Modules\\Settings\\' => 
        array (
            0 => __DIR__ . '/../..' . '/',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
        'Modules\\Settings\\Contracts\\SettingsPage' => __DIR__ . '/../..' . '/Contracts/SettingsPage.php',
        'Modules\\Settings\\Contracts\\SyncEnv' => __DIR__ . '/../..' . '/Contracts/SyncEnv.php',
        'Modules\\Settings\\Data\\ValueObjects\\CustomFields' => __DIR__ . '/../..' . '/Data/ValueObjects/CustomFields.php',
        'Modules\\Settings\\Database\\Seeders\\SettingsDatabaseSeeder' => __DIR__ . '/../..' . '/Database/Seeders/SettingsDatabaseSeeder.php',
        'Modules\\Settings\\Models\\Settings' => __DIR__ . '/../..' . '/Models/Settings.php',
        'Modules\\Settings\\Nova\\Flexible\\Layouts\\CustomFieldsLayout' => __DIR__ . '/../..' . '/Nova/Flexible/Layouts/CustomFieldsLayout.php',
        'Modules\\Settings\\Nova\\Flexible\\Layouts\\EnvOptionLayout' => __DIR__ . '/../..' . '/Nova/Flexible/Layouts/EnvOptionLayout.php',
        'Modules\\Settings\\Nova\\Flexible\\Layouts\\EnvOptionProtectedLayout' => __DIR__ . '/../..' . '/Nova/Flexible/Layouts/EnvOptionProtectedLayout.php',
        'Modules\\Settings\\Nova\\Flexible\\Presets\\CustomFieldsPreset' => __DIR__ . '/../..' . '/Nova/Flexible/Presets/CustomFieldsPreset.php',
        'Modules\\Settings\\Nova\\Flexible\\Presets\\EnvOptionsPreset' => __DIR__ . '/../..' . '/Nova/Flexible/Presets/EnvOptionsPreset.php',
        'Modules\\Settings\\Observers\\SettingsObserver' => __DIR__ . '/../..' . '/Observers/SettingsObserver.php',
        'Modules\\Settings\\Pages\\MailSettings' => __DIR__ . '/../..' . '/Pages/MailSettings.php',
        'Modules\\Settings\\Pages\\Page' => __DIR__ . '/../..' . '/Pages/Page.php',
        'Modules\\Settings\\Providers\\RouteServiceProvider' => __DIR__ . '/../..' . '/Providers/RouteServiceProvider.php',
        'Modules\\Settings\\Providers\\SettingsServiceProvider' => __DIR__ . '/../..' . '/Providers/SettingsServiceProvider.php',
        'Modules\\Settings\\Services\\SettingsService' => __DIR__ . '/../..' . '/Services/SettingsService.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInite21157ee29f4a3941834efcbf8101477::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInite21157ee29f4a3941834efcbf8101477::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInite21157ee29f4a3941834efcbf8101477::$classMap;

        }, null, ClassLoader::class);
    }
}