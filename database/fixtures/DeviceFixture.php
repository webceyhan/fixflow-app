<?php

namespace Database\Fixtures;

class DeviceFixture extends Fixture
{
    protected static string $name = 'devices';

    /**
     * Return a closure that sets model, brand, color, and IMEI based on the device type.
     */
    public static function deviceByType(): \Closure
    {
        $models = static::data('models');

        return function (array $attributes) use ($models) {
            $type = $attributes['type'];
            $data = fake()->randomElement($models[$type->value]);
            $version = fake()->randomElement($data['versions']);

            return [
                'model' => "{$data['name']} {$version}",
                'brand' => $data['brand'],
                'color' => fake()->optional()->randomElement($data['colors']),
                'imei' => fake()->optional($type->hasCellular())->numerify('###############'),
            ];
        };
    }
}
