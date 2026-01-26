<?php

namespace Database\Factories\States;

use BackedEnum;

trait PriorityStates
{
    private const PRIORITY = 'priority';

    /**
     * Indicate that the model has a specific priority.
     */
    public function ofPriority(BackedEnum $priority): self
    {
        return $this->state(fn (array $attributes) => [
            static::PRIORITY => $priority,
        ]);
    }
}
