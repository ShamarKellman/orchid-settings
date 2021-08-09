<?php

namespace ShamarKellman\Settings\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Foundation\Testing\WithFaker;
use ShamarKellman\Settings\Models\Setting;

class SettingFactory extends Factory
{
    use WithFaker;

    protected $model = Setting::class;

    public function definition(): array
    {
        return [
            [
                'key' => 'site_adress',
                'value' => $this->faker->streetAddress,
            ],
            [
                'key' => 'site_description',
                'value' => $this->faker->sentence($nbWords = 6, $variableNbWords = true),
            ],
            [
                'key' => 'site_email',
                'value' => $this->faker->companyEmail,
            ],
            [
                'key' => 'site_keywords',
                'value' => implode(', ', $this->faker->words($nb = 5, $asText = false)),
            ],
            [
                'key' => 'site_phone',
                'value' => $this->faker->tollFreePhoneNumber,
            ],
            [
                'key' => 'site_title',
                'value' => $this->faker->catchPhrase,
            ],
            [
                'key' => 'anykey',
                'value' => $this->faker->catchPhrase,
            ],
        ];
    }
}
