<?php

namespace Database\Factories\States;

trait ActiveStatusStates
{
    private const STATUS = 'status';

    /**
     * Indicate that the model's status is active.
     */
    public function active(): self
    {
        return $this->state(fn (array $attributes) => [
            self::STATUS => $attributes[self::STATUS]::activeCases()[0],
        ]);
    }
}
