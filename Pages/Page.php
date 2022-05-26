<?php

namespace Modules\Settings\Pages;


use Illuminate\Support\Str;
use Modules\Settings\Contracts\SettingsPage;
use Modules\Settings\Nova\Flexible\Layouts\CustomFieldsLayout;
use Modules\Settings\Nova\Flexible\Presets\CustomFieldsPreset;
use Whitecube\NovaFlexibleContent\Flexible;

abstract class Page implements SettingsPage
{

    protected array $fillable = [];
    protected array $guarded = [];
    protected array $casts = [];

    public function name(): string
    {
        return Str::of(static::class)
            ->classBasename()
            ->replace('Settings', '')
            ->headline();
    }


    public function slug(): string
    {
        return Str::of($this->name())
            ->ucsplit()
            ->join('-');
    }

    public function options(): array
    {
        return $this->fillable;
    }

    public function casts(): array
    {
        return $this->casts;
    }

    public abstract function fields(): array;

    public function defaultValues(): array
    {
        return [];
    }

    public function guarded(): array
    {
        return $this->guarded;
    }
}
