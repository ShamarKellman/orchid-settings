<?php

namespace ShamarKellman\Settings\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static Setting set(string $name, string | array $value)
 * @method static Setting get(string $name, mixed | null $default)
 * @method static Setting forget(string $name)
 * @method static Setting getNoCache(string $name, mixed | null $default)
 */
class Setting extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \ShamarKellman\Settings\Models\Setting::class;
    }
}
