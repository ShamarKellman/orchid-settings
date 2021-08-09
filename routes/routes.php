<?php

use Illuminate\Support\Facades\Route;
use Orchid\Support\Facades\Dashboard;
use ShamarKellman\Settings\Screens\SettingEdit;
use ShamarKellman\Settings\Screens\SettingList;


Route::domain((string) config('platform.domain'))
    ->prefix(Dashboard::prefix('/systems'))
    ->as('platform.setting.')
    ->middleware(config('platform.middleware.private'))
    ->group(function () {
        Route::screen('setting/{setting}/edit', SettingEdit::class)
            ->name('edit');

        if(config('orchid-settings.allow_create', 'false')) {
            Route::screen('setting/create', SettingEdit::class)
                ->name('create');
        }

        Route::screen('setting', SettingList::class)
            ->name('list');
    });


