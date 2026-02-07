<?php

namespace Database\Fixtures;

class CustomerFixture extends Fixture
{
    protected static string $name = 'customers';

    /**
     * Return a closure that sets the note based on the customer's status.
     */
    public static function noteByStatus(): \Closure
    {
        $notes = static::data('notes');

        return fn (array $attributes) => [
            'note' => fake()->randomElement($notes[$attributes['status']->value]),
        ];
    }
}
