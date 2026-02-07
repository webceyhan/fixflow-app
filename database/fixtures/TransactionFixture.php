<?php

namespace Database\Fixtures;

class TransactionFixture extends Fixture
{
    protected static string $name = 'transactions';

    /**
     * Return a closure that sets the note based on the transaction type.
     */
    public static function noteByType(): \Closure
    {
        $notes = static::data('notes');

        return function (array $attributes) use ($notes) {
            $type = $attributes['type']->value;
            $typeNotes = $notes[$type];

            return [
                'note' => fake()->randomElement($typeNotes),
            ];
        };
    }
}
