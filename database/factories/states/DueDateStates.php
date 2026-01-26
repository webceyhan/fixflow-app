<?php

namespace Database\Factories\States;

trait DueDateStates
{
    private const DUE_DATE = 'due_date';

    /**
     * Indicate that the model's status is active.
     */
    abstract public function active(): self;

    /**
     * Indicate that the model is due in specific number of days.
     * Defaults to 2 days (due soon).
     */
    public function dueInDays(int $days = 2): self
    {
        return $this->state(fn (array $attributes) => [
            static::DUE_DATE => now()->addDays($days)->startOfDay(),
        ]);
    }

    /**
     * Indicate that the ticket is overdue.
     */
    public function overdue(): self
    {
        return $this->dueInDays(-1)->active();
    }
}
