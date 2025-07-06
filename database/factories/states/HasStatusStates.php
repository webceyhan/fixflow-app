<?php

namespace Database\Factories\States;

use BackedEnum;

trait HasStatusStates
{
    private const STATUS = 'status';

    /**
     * Set the model to a specific status.
     */
    public function ofStatus(BackedEnum $status): self
    {
        return $this->state(fn (array $attributes) => [
            static::STATUS => $status,
        ]);
    }
}
