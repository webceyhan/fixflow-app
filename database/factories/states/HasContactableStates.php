<?php

namespace Database\Factories\States;

trait HasContactableStates
{
    private const EMAIL = 'email';

    private const PHONE = 'phone';

    /**
     * Indicate that the model has an email address.
     */
    public function mailable(): self
    {
        return $this->state(fn (array $attributes) => [
            static::EMAIL => fake()->unique()->safeEmail(),
        ]);
    }

    /**
     * Indicate that the model has no email address.
     */
    public function notMailable(): self
    {
        return $this->state(fn (array $attributes) => [
            static::EMAIL => null,
        ]);
    }

    /**
     * Indicate that the model has a phone number.
     */
    public function callable(): self
    {
        return $this->state(fn (array $attributes) => [
            static::PHONE => fake()->unique()->phoneNumber(),
        ]);
    }

    /**
     * Indicate that the model has no phone number.
     */
    public function notCallable(): self
    {
        return $this->state(fn (array $attributes) => [
            static::PHONE => null,
        ]);
    }

    /**
     * Indicate that the model is contactable (has email or phone).
     */
    public function contactable(): self
    {
        return $this->mailable()->callable();
    }

    /**
     * Indicate that the model is not contactable (no email or phone).
     */
    public function notContactable(): self
    {
        return $this->notMailable()->notCallable();
    }
}
