<?php

namespace Database\Factories\States;

use Illuminate\Support\Arr;

trait HasProgressStates
{
    private const STATUS = 'status';

    /**
     * Indicate that the model is pending.
     */
    public function pending(): self
    {
        return $this->state(fn (array $attributes) => [
            static::STATUS => Arr::first($attributes[static::STATUS]::pendingCases()),
        ]);
    }

    /**
     * Indicate that the model is complete.
     */
    public function complete(): self
    {
        return $this->state(fn (array $attributes) => [
            static::STATUS => Arr::first($attributes[static::STATUS]::completeCases()),
        ]);
    }
}
