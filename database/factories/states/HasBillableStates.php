<?php

namespace Database\Factories\States;

trait HasBillableStates
{
    private const IS_BILLABLE = 'is_billable';

    /**
     * Indicate that the model is billable.
     */
    public function billable(): self
    {
        return $this->state(fn (array $attributes) => [
            static::IS_BILLABLE => true,
        ]);
    }

    /**
     * Indicate that the model is not billable.
     */
    public function notBillable(): self
    {
        return $this->state(fn (array $attributes) => [
            static::IS_BILLABLE => false,
        ]);
    }
}
