<?php

namespace Database\Factories;

use App\Enums\Priority;
use App\Enums\TaskStatus;
use App\Enums\TaskType;
use App\Models\Task;
use App\Models\Ticket;
use Database\Factories\States\ActiveStatusStates;
use Database\Factories\States\ApprovalStates;
use Database\Factories\States\AssignmentStates;
use Database\Factories\States\BillingStates;
use Database\Factories\States\CancelledStatusStates;
use Database\Factories\States\NoteStates;
use Database\Factories\States\PriorityStates;
use Database\Factories\States\StatusStates;
use Database\Factories\States\TypeStates;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Task>
 */
class TaskFactory extends Factory
{
    use ActiveStatusStates,
        ApprovalStates,
        AssignmentStates,
        BillingStates,
        CancelledStatusStates,
        NoteStates,
        PriorityStates,
        StatusStates,
        TypeStates;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'ticket_id' => Ticket::factory(),
            'assignee_id' => null,
            'cost' => fake()->randomFloat(2, 10, 500),
            'is_billable' => fake()->boolean(80),
            'note' => null,
            'priority' => fake()->randomElement(Priority::cases()),
            'type' => fake()->randomElement(TaskType::cases()),
            'status' => TaskStatus::Created,
            'approved_at' => null,
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
            'assignee_id' => $ticket->assignee_id,
            'priority' => $ticket->priority,
            'created_at' => fake()->dateTimeBetween($ticket->created_at),
        ]);
    }

    // STATES //////////////////////////////////////////////////////////////////////////////////////

    /**
     * Indicate that the task is completed.
     */
    public function completed(): self
    {
        return $this->ofStatus(TaskStatus::Completed)->approved();
    }
}
