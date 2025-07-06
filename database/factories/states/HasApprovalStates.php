<?php

namespace Database\Factories\States;

trait HasApprovalStates
{
    private const APPROVED_AT = 'approved_at';

    /**
     * Indicate that the model is approved.
     */
    public function approved(): self
    {
        return $this->state(fn (array $attributes) => [
            static::APPROVED_AT => now(),
        ]);
    }

    /**
     * Indicate that the model is unapproved.
     */
    public function unapproved(): self
    {
        return $this->state(fn (array $attributes) => [
            static::APPROVED_AT => null,
        ]);
    }
}
