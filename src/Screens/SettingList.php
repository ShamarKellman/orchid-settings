<?php

namespace ShamarKellman\Settings\Screens;

use Orchid\Screen\Actions\Link;
use Orchid\Screen\Screen;
use ShamarKellman\Settings\Layouts\SettingListLayout;
use ShamarKellman\Settings\Models\Setting;

class SettingList extends Screen
{
    public $name = 'Setting List';

    public $description = 'List all settings';

    public function query(): array
    {
        $this->name = __('Setting List');

        return [
            'settings' => Setting::filters()->defaultSort('key', 'desc')->paginate(30),
        ];
    }

    public function commandBar(): array
    {
        return [
            Link::make('Create a new setting')
                ->route('platform.setting.create'),
        ];
    }

    public function layout(): array
    {
        return [
            SettingListLayout::class,
        ];
    }
}
