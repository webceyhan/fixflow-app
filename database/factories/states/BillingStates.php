<?php

namespace Database\Factories\States;

trait BillingStates
{
    private const IS_BILLABLE = 'is_billable';

    /**
     * Indicate that the model is billable.
     */
    public function billable(): self
    {
        return $this->state(fn (array $attributes) => [
            self::IS_BILLABLE => true,
        ]);
    }

    /**
     * Indicate that the model is not billable.
     */
    public function notBillable(): self
    {
        return $this->state(fn (array $attributes) => [
            self::IS_BILLABLE => false,
        ]);
    }
}
