<?php

namespace Database\Factories\States;

trait CancelledStatusStates
{
    private const STATUS = 'status';

    /**
     * Indicate that the model's status is cancelled.
     */
    public function cancelled(): self
    {
        return $this->state(fn (array $attributes) => [
            static::STATUS => 'cancelled',
        ]);
    }
}
