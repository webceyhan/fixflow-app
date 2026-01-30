<?php

namespace Database\Factories\States;

trait ApprovalStates
{
    private const APPROVED_AT = 'approved_at';

    /**
     * Indicate that the model is approved.
     */
    public function approved(): self
    {
        return $this->state(fn (array $attributes) => [
            'approved_at' => fake()->dateTimeBetween('-30 days', 'now'),
        ]);
    }

    /**
     * Indicate that the model is not approved.
     */
    public function unapproved(): self
    {
        return $this->state(fn (array $attributes) => [
            self::APPROVED_AT => null,
        ]);
    }
}
