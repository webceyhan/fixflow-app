<?php

namespace Database\Factories\States;

use App\Enums\Priority;

trait HasPriorityStates
{
    private const PRIORITY = 'priority';

    /**
     * Indicate that the model is of the given priority.
     */
    public function ofPriority(Priority $priority): self
    {
        return $this->state(fn (array $attributes) => [
            static::PRIORITY => $priority,
        ]);
    }

    /**
     * Indicate that the model has low priority.
     */
    public function lowPriority(): self
    {
        return $this->ofPriority(Priority::Low);
    }

    /**
     * Indicate that the model has medium priority.
     */
    public function mediumPriority(): self
    {
        return $this->ofPriority(Priority::Medium);
    }

    /**
     * Indicate that the model has high priority.
     */
    public function highPriority(): self
    {
        return $this->ofPriority(Priority::High);
    }

    /**
     * Indicate that the model has urgent priority.
     */
    public function urgent(): self
    {
        return $this->ofPriority(Priority::Urgent);
    }
}
