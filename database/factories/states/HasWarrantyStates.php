<?php

namespace Database\Factories\States;

trait HasWarrantyStates
{
    private const WARRANTY_EXPIRE_DATE = 'warranty_expire_date';

    /**
     * Indicate that the model has warranty.
     */
    public function withWarranty(): self
    {
        return $this->state(fn (array $attributes) => [
            static::WARRANTY_EXPIRE_DATE => now()->addYear(),
        ]);
    }

    /**
     * Indicate that the model has no warranty.
     */
    public function withoutWarranty(): self
    {
        return $this->state(fn (array $attributes) => [
            static::WARRANTY_EXPIRE_DATE => null,
        ]);
    }
}
