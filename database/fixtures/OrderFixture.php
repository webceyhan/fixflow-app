<?php

namespace Database\Fixtures;

class OrderFixture extends Fixture
{
    protected static string $name = 'orders';

    /**
     * Return a closure that sets the part name and supplier.
     */
    public static function partsAndSupplier(): \Closure
    {
        $parts = static::data('parts');
        $suppliers = static::data('suppliers');

        return fn (array $attributes) => [
            'name' => fake()->randomElement($parts),
            'supplier' => fake()->randomElement($suppliers),
        ];
    }
}
