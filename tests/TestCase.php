<?php

namespace ShamarKellman\Settings\Tests;

use Illuminate\Database\Eloquent\Factories\Factory;
use Orchestra\Testbench\TestCase as Orchestra;
use ShamarKellman\Settings\SettingsServiceProvider;

class TestCase extends Orchestra
{
    public function setUp(): void
    {
        parent::setUp();

        Factory::guessFactoryNamesUsing(
            fn (string $modelName) => 'ShamarKellman\\Setting\\Database\\Factories\\'.class_basename($modelName).'Factory'
        );
    }

    protected function getPackageProviders($app): array
    {
        return [
            SettingsServiceProvider::class,
        ];
    }

    public function getEnvironmentSetUp($app)
    {
        $config = config();

        $config->set('app.debug', true);

        $config->set('database.connections.orchid', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);

        $config->set('scout.driver', 'array');
        $config->set('database.default', 'orchid');

        include_once __DIR__.'/../database/migrations/create_settings_table.php.stub';
        (new \CreateSettingsTable())->up();
    }
}
