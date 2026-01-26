<?php

namespace Database\Factories\States;

use App\Models\User;

trait AssignmentStates
{
    private const ASSIGNEE_ID = 'assignee_id';

    /**
     * Indicate that the model is assigned to a specific user.
     */
    public function forAssignee(User $user): self
    {
        return $this->state(fn (array $attributes) => [
            static::ASSIGNEE_ID => $user->id,
        ]);
    }

    /**
     * Indicate that the model is assigned.
     */
    public function assigned(): self
    {
        return $this->state(fn (array $attributes) => [
            static::ASSIGNEE_ID => User::factory(),
        ]);
    }

    /**
     * Indicate that the model is unassigned.
     */
    public function unassigned(): self
    {
        return $this->state(fn (array $attributes) => [
            static::ASSIGNEE_ID => null,
        ]);
    }
}
