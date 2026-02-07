<?php

namespace Database\Fixtures;

class AdjustmentFixture extends Fixture
{
    protected static string $name = 'adjustments';

    /**
     * Return a closure that sets the note based on the adjustment reason.
     */
    public static function noteByReason(): \Closure
    {
        $notes = static::data('notes');

        return function (array $attributes) use ($notes) {
            $reason = $attributes['reason']->value;
            $reasonNotes = $notes[$reason];

            return [
                'note' => fake()->randomElement($reasonNotes),
            ];
        };
    }
}
