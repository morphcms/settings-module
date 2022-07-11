<?php

namespace Modules\Settings\Services;

use Illuminate\Support\Collection;
use Jackiedo\DotenvEditor\Facades\DotenvEditor;
use JetBrains\PhpStorm\Pure;
use Modules\Settings\Contracts\SettingsPage;
use Modules\Settings\Contracts\SyncEnv;
use Modules\Settings\Data\ValueObjects\CustomFields;
use Modules\Settings\Nova\Flexible\Layouts\CustomFieldsLayout;
use Modules\Settings\Nova\Flexible\Layouts\EnvOptionLayout;
use Modules\Settings\Nova\Flexible\Layouts\EnvOptionProtectedLayout;
use Outl1ne\NovaSettings\NovaSettings;
use Whitecube\NovaFlexibleContent\Concerns\HasFlexible;
use Whitecube\NovaFlexibleContent\Value\FlexibleCast;

class SettingsService extends Collection
{
    use HasFlexible;

    private array $pages = [];

    private array $settings = [];

    private array $casts = [];

    /**
     * Settings constructor.
     * Disable the normal construct and initialise it using the nova settings.
     *
     * @param  array  $pages
     *
     * @throws \Exception
     */
    public function __construct(array $pages = [])
    {
        parent::__construct(
            $this->hydratePages($pages)
                //->resolve()
        );
    }

    private function resolve(): Collection
    {
        foreach ($this->pages as $page) {
            $this->settings = array_merge($this->settings, $page->options());
            $this->casts = array_merge($this->casts, $page->casts());
        }

        return $this->solveFlexibleCasts(nova_get_settings($this->settings));
    }

    /**
     * Returns an array with the correct casted values.
     * We have to do this because NovaSettings doesn't fully support FlexibleCast.
     *
     * @param  array  $settings
     * @param  array|null  $casts
     * @return Collection
     */
    private function solveFlexibleCasts(array $settings, array $casts = null): Collection
    {
        return collect($settings)->map(function ($setting, $name) use ($casts) {
            return $this->isFlexible($name, $casts ?? $this->casts)
                // Cast the value to flexible. We have to do this because NovaSettings casts it only when it saves it to the database and not when retrieving it.
                ? $this->toFlexible($setting)
                // use the default casting done by the NovaSettings.
                : $setting;
        });
    }

    /**
     * Determines if the setting is in the casts array and is set to FlexibleCast.
     *
     * @param $settingName
     * @param  array  $casts
     * @return bool
     */
    #[Pure]
 private function isFlexible($settingName, array $casts = []): bool
 {
     return array_key_exists($settingName, $casts) && $casts[$settingName] === FlexibleCast::class;
 }

    private function hydratePages(array $pages): static
    {
        foreach ($pages as $page) {
            $instance = app($page);
            if ($instance instanceof SettingsPage) {
                $this->pages[$page] = $instance;
            } else {
                throw new \Exception("The page {$page} is not implementing the SettingsPage contract.");
            }
        }

        return $this;
    }

    public function boot()
    {
        // Collect Settings
        foreach ($this->pages as $page) {
            if ($page instanceof SettingsPage) {
                NovaSettings::addSettingsFields($page->fields(), $page->casts(), $page->name());
            }
        }
    }

    public function seedDefaults()
    {
        foreach ($this->pages as $page) {
            $defaults = $page->defaultValues();

            if (empty($defaults)) {
                continue;
            }

            foreach ($defaults as $key => $value) {
                nova_set_setting_value($key, $value);
            }
        }
    }

    public function getFromPage(string $page): Collection
    {
        $instance = $this->getPage($page);

        return collect(nova_get_settings($instance->options()));
    }

    public function getPage($page): SettingsPage
    {
        return $this->pages[$page];
    }

    public function customFields(string $key): CustomFields
    {
        return new CustomFields($this->get($key));
    }

    public function syncWithEnv(string $key, mixed $value)
    {
        $filteredSettings = $this->pages()
            ->filter(fn ($page) => $page instanceof SyncEnv)
            ->flatMap(fn (SettingsPage $page) => $page->options());

        if (! in_array($key, $filteredSettings->toArray())) {
            return;
        }

        $editor = DotenvEditor::load();

        if ($this->isFlexible($key, $this->casts)) {
            $options = $this->toFlexible($value, [
                'env-option-field' => EnvOptionLayout::class,
                'env-protected-option-field' => EnvOptionProtectedLayout::class,
                'custom-field' => CustomFieldsLayout::class,
            ])->mapWithKeys(fn ($layout) => [strtoupper($layout->key) => $layout->value])
                ->toArray();

            $editor->setKeys($options);
        } else {
            $editor->setKey(strtoupper($key), $value);
        }

        $editor->save();
    }

    public function pages(): Collection
    {
        return collect($this->pages);
    }

    /**
     * Returns the primary value from a flexible setting
     *
     * @param  string  $flexibleSettingKey The flexible setting that holds the layouts
     * @param  string  $attributeName The attribute value to be retrieved. Default 'value'
     * @param  string  $primaryKey The key that determines if the layout is primary. Default 'is_primary'
     * @return mixed|null
     *
     * @throws \Exception
     */
    public function primary(string $flexibleSettingKey, string $attributeName = 'value', string $primaryKey = 'is_primary'): mixed
    {
        if (! $this->isFlexible($flexibleSettingKey)) {
            throw new \Exception("Your are trying to access the setting '{$flexibleSettingKey}' which is not casted as flexible.");
        }

        $setting = $this->get($flexibleSettingKey);

        if (is_null($setting)) {
            return null;
        }

        $value = $setting->filter(function ($item) use ($primaryKey) {
            return $item[$primaryKey];
        });

        if ($value->isEmpty()) {
            $value = $setting;
        }

        return $value->first()?->{$attributeName};
    }
}
