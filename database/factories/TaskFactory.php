<?php

namespace Database\Factories;

use App\Enums\TaskStatus;
use App\Enums\TaskType;
use App\Models\Ticket;
use Database\Factories\States\HasApprovalStates;
use Database\Factories\States\HasBillableStates;
use Database\Factories\States\HasNoteStates;
use Database\Factories\States\HasProgressStates;
use Database\Factories\States\HasStatusStates;
use Database\Factories\States\HasTypeStates;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Task>
 */
class TaskFactory extends Factory
{
    use HasApprovalStates, HasBillableStates, HasNoteStates, HasProgressStates, HasStatusStates, HasTypeStates;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'ticket_id' => Ticket::factory(),
            'cost' => fake()->randomFloat(2, 10, 100),
            'is_billable' => true,
            'note' => fake()->sentence(),
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
}
