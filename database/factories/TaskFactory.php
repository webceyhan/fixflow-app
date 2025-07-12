<?php

namespace Database\Factories;

use App\Enums\TaskStatus;
use App\Enums\TaskType;
use App\Models\Ticket;
use Database\Factories\States\HasApprovalStates;
use Database\Factories\States\HasBillableStates;
use Database\Factories\States\HasProgressStates;
use Database\Factories\States\HasStatusStates;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Task>
 */
class TaskFactory extends Factory
{
    use HasApprovalStates, HasBillableStates, HasProgressStates, HasStatusStates;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'ticket_id' => Ticket::factory(),
            'description' => fake()->sentence(),
            'cost' => fake()->randomFloat(2, 10, 100),
            'is_billable' => true,
            'type' => TaskType::Repair,
            'status' => TaskStatus::New,
            'approved_at' => now(),
        ];
    }

    // RELATIONS ///////////////////////////////////////////////////////////////////////////////////

    /**
     * Indicate that the task belongs to a specific ticket.
     */
    public function forTicket(Ticket $ticket): self
    {
        return $this->state(fn (array $attributes) => [
            'ticket_id' => $ticket->id,
            'created_at' => $ticket->created_at,
        ]);
    }

    // STATES //////////////////////////////////////////////////////////////////////////////////////

    /**
     * Indicate that the task is of a specific type.
     */
    public function ofType(TaskType $type): self
    {
        return $this->state(fn (array $attributes) => [
            'type' => $type,
        ]);
    }
}
