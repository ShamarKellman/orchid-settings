<?php

namespace ShamarKellman\Settings;

use Illuminate\Support\Facades\View;
use Orchid\Platform\ItemPermission;
use Orchid\Support\Facades\Dashboard;
use ShamarKellman\Settings\Composers\MenuComposer;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class SettingsServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('orchid-settings')
            ->hasConfigFile()
            ->hasRoute('routes')
            ->hasMigration('create_settings_table');
    }

    protected function registerPermissions(): ItemPermission
    {
        return ItemPermission::group(__('System'))
            ->addPermission('platform.systems.setting', __('Settings'));
    }

    public function packageBooted()
    {
        $this->app->booted(function () {
            Dashboard::registerPermissions($this->registerPermissions());

            View::composer('platform::dashboard', MenuComposer::class);
        });
    }
}
