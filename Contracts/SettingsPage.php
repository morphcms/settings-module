<?php


namespace Modules\Settings\Contracts;


interface SettingsPage
{

    public function name(): string;
    public function options(): array;
    public function casts(): array;
    public function fields() : array;
    public function defaultValues(): array;
    public function guarded(): array;
    public function slug();
}
