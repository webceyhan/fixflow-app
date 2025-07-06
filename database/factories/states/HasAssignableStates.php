<?php

namespace Database\Factories\States;

use App\Models\User;

trait HasAssignableStates
{
    /**
     * Indicate that the model is assigned to a specific user.
     */
    public function forAssignee(User $user): self
    {
        return $this->state(fn (array $attributes) => [
            'assignee_id' => $user->id,
        ]);
    }

    /**
     * Indicate that the model is assigned to a user.
     */
    public function assigned(): self
    {
        return $this->state(fn (array $attributes) => [
            'assignee_id' => User::factory(),
        ]);
    }

    /**
     * Indicate that the model is not assigned to any user.
     */
    public function unassigned(): self
    {
        return $this->state(fn (array $attributes) => [
            'assignee_id' => null,
        ]);
    }
}
