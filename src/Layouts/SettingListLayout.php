<?php

namespace ShamarKellman\Settings\Layouts;

use Illuminate\Support\Str;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

class SettingListLayout extends Table
{
    public $target = 'settings';

    public function columns(): array
    {
        return  [
            TD::make('key', 'Key')
                ->sort()
                ->filter('text')
                ->Render(function ($data) {
                    return Link::make($data->key)
                        ->route('platform.setting.edit', $data->key);
                }),
            TD::make('options.title', 'Name')
                ->render(function ($setting) {
                    return $setting->options['title'];
                })
                ->sort(),
            TD::make('value', 'Value')
                ->render(function ($setting) {
                    if (is_array($setting->value)) {
                        return Str::limit(htmlspecialchars(json_encode($setting->value)), 50);
                    }

                    return Str::limit(htmlspecialchars($setting->value), 50);
                })
                ->sort()
                ->filter('text'),


        ];
    }
}
