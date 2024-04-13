<?php

namespace Database\Factories;

use App\Enums\TaskType;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Task>
 */
class TaskFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'description' => fake()->sentence(),
            'cost' => fake()->randomFloat(2, 10, 100),
            'is_billable' => true,
            'type' => TaskType::Repair,
        ];
    }

    // STATES //////////////////////////////////////////////////////////////////////////////////////

    /**
     * Indicate that the task is free of charge.
     */
    public function free(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_billable' => false,
        ]);
    }

    /**
     * Indicate that the task has the specified type.
     */
    public function ofType(TaskType $type): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => $type,
        ]);
    }
}
