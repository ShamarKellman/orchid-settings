<?php

namespace ShamarKellman\Settings\Screens;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\LayoutFactory;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Alert;
use ShamarKellman\Settings\Layouts\SettingEditLayout;
use ShamarKellman\Settings\Models\Setting;

class SettingEdit extends Screen
{
    public $name = 'Setting edit';

    public $description = 'Edit setting';

    public bool $edit = true;

    public function query(Setting $setting = null): array
    {
        if (! $setting->exists) {
            $setting = new Setting();
            $this->edit = false;
            $this->name = __('New setting');
            $this->description = __('Add new setting');
        } else {
            $this->edit = true;
            $this->name = __(`Edit setting {$setting->key}`);
            if (! is_null($setting->options['title'])) {
                $this->description = $setting->options['title'];
            }
        }

        return [
            'setting' => $setting,
        ];
    }

    public function commandBar(): array
    {
        return [
            Link::make(__('Back to list'))->icon('icon-arrow-left-circle')->route('platform.setting.list'),
            Button::make(__('Save'))->icon('icon-check')->method('save'),
            Button::make(__('Remove'))->icon('icon-close')->method('remove')->canSee($this->edit),
        ];
    }

    public function layout(): array
    {
        return [
            LayoutFactory::columns([
                'EditSetting' => [
                    new SettingEditLayout($this->edit),
                ],
            ]),
        ];
    }

    public function save(Setting $setting, Request $request): RedirectResponse
    {
        $req = $request->get('setting');

        if ($req['options']['type'] == 'code') {
            $req['value'] = json_decode($req['value'], true);
        }

        $setting->updateOrCreate(['key' => $req['key']], $req);
        $setting->refresh()->cacheErase($setting->key);

        Alert::info(__('Setting was saved'));

        return redirect()->route('platform.setting.list');
    }

    public function remove(Setting $setting): RedirectResponse
    {
        $setting->delete();

        Alert::info(__('Setting was removed'));

        return redirect()->route('platform.setting.list');
    }
}
