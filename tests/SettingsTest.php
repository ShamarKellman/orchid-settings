<?php

namespace ShamarKellman\Settings\Tests;

use Illuminate\Support\Str;
use ShamarKellman\Settings\Models\Setting;
use stdClass;

class SettingsTest extends TestCase
{
    public Setting $setting;

    public function testForOneValue(): void
    {
        $key = 'test-' . Str::random(40);
        $value = 'value-' . Str::random(40);

        $this->setting->set($key, $value);

        $result = $this->setting->get($key);

        $this->assertEquals($value, $result);

        $this->setting->forget($key);

        $result = $this->setting->get($key);

        $this->assertEquals(null, $result);
    }

    public function testForManyValue(): void
    {
        $valueArray = [
            'test-1' => 'value-' . Str::random(40),
            'test-2' => 'value-' . Str::random(40),
            'test-3' => 'value-' . Str::random(40),
        ];

        foreach ($valueArray as $key => $value) {
            $this->setting->set($key, $value);
        }

        $result = $this->setting->get([
            'test-1',
            'test-2',
            'test-3',
        ]);

        $this->assertCount(3, $result);

        $result = $this->setting->forget([
            'test-1',
            'test-2',
            'test-3',
        ]);

        $this->assertEquals(3, $result);
    }

    public function testForRewriteCache(): void
    {
        $this->setting->set('cache-key', 'old');
        $this->setting->get('cache-key');

        $this->setting->set('cache-key', 'new');
        $this->assertStringContainsString('new', $this->setting->get('cache-key'));
    }

    /**
     * @dataProvider notExistValues
     *
     * @param $defaultValue
     */
    public function testDefaultValue($defaultValue): void
    {
        $value = $this->setting->get('nonexistent value', $defaultValue);

        $this->assertEquals(gettype($defaultValue), gettype($value));
        $this->assertEquals($defaultValue, $value);
    }

    public function notExistValues(): array
    {
        return [
            ['string'],
            [123],
            [new stdClass()],
            [['test', 123]],
        ];
    }

    public function testUseHelper(): void
    {
        $this->setting->set('helper', 'run');

        $this->assertEquals('run', setting('helper'));

        $this->assertEquals('default', setting('not-found', 'default'));
    }

    public function setUp(): void
    {
        parent::setUp();
        $setting = new Setting();
        $setting->cache = false;
        $this->setting = $setting;
    }
}
