<?php

namespace Database\Factories\States;

use BackedEnum;

trait HasTypeStates
{
    private const TYPE = 'type';

    /**
     * Set the model to a specific type.
     */
    public function ofType(BackedEnum $type): self
    {
        return $this->state(fn (array $attributes) => [
            static::TYPE => $type,
        ]);
    }
}
