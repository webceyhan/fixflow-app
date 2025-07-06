<?php

namespace Database\Factories\States;

trait HasBillableStates
{
    private const IS_BILLABLE = 'is_billable';

    /**
     * Get the name of the billable attribute.
     * This can be overridden in the factory to specify a different field.
     */
    public function getBillableName(): string
    {
        return 'cost';
    }

    /**
     * Indicate that the model is billable.
     */
    public function billable(?float $value = null): self
    {
        return $this->state(fn (array $attributes) => [
            static::IS_BILLABLE => true,
            $this->getBillableName() => $value ?? fake()->randomFloat(2, 10, 100),
        ]);
    }

    /**
     * Indicate that the model is not billable.
     */
    public function notBillable(): self
    {
        return $this->state(fn (array $attributes) => [
            static::IS_BILLABLE => false,
            $this->getBillableName() => 0,
        ]);
    }
}
