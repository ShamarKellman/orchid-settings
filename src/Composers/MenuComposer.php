<?php

namespace ShamarKellman\Settings\Composers;

use Orchid\Platform\Dashboard;
use Orchid\Screen\Actions\Menu;

class MenuComposer
{
    public Dashboard $dashboard;

    public function __construct(Dashboard $dashboard)
    {
        $this->dashboard = $dashboard;
    }

    public function compose()
    {
        $this->dashboard->registerMenuElement(
            Dashboard::MENU_MAIN,
            Menu::make(__('Settings'))
                ->icon('settings')
                ->route('platform.setting.list')
                ->permission('platform.systems.setting')
                ->sort(1)
        );
    }
}
