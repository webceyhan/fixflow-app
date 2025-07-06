<?php

namespace Database\Factories\States;

trait HasDueDateStates
{
    private const DUE_DATE = 'due_date';

    /**
     * Indicate that the model is due in specific number of days.
     * Defaults to 2 days (due soon).
     */
    public function dueInDays(int $days = 2): static
    {
        return $this->state(fn (array $attributes) => [
            static::DUE_DATE => now()->addDays($days)->startOfDay(),
        ]);
    }

    /**
     * Indicate that the model is overdue.
     */
    public function overdue(): static
    {
        return $this->dueInDays(-1)->pending();
    }
}
