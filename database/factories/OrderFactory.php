<?php

namespace Database\Factories;

use App\Enums\OrderStatus;
use App\Models\Ticket;
use Database\Factories\States\ActiveStatusStates;
use Database\Factories\States\ApprovalStates;
use Database\Factories\States\AssignmentStates;
use Database\Factories\States\BillingStates;
use Database\Factories\States\CancelledStatusStates;
use Database\Factories\States\DueDateStates;
use Database\Factories\States\NoteStates;
use Database\Factories\States\StatusStates;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{
    use ActiveStatusStates,
        ApprovalStates,
        AssignmentStates,
        BillingStates,
        CancelledStatusStates,
        DueDateStates,
        NoteStates,
        StatusStates;

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
            'name' => fake()->words(3, true),
            'url' => fake()->optional()->url(),
            'supplier' => fake()->optional()->company(),
            'quantity' => fake()->numberBetween(1, 5),
            'cost' => fake()->randomFloat(2, 10, 500),
            'is_billable' => fake()->boolean(80),
            'note' => null,
            'due_date' => fake()->dateTimeBetween('now', '+2 weeks'),
            'status' => OrderStatus::Created,
            'approved_at' => null,
        ];
    }

    // RELATIONS ///////////////////////////////////////////////////////////////////////////////////

    /**
     * Indicate that the order belongs to a specific ticket.
     */
    public function forTicket(Ticket $ticket): static
    {
        return $this->state(fn (array $attributes) => [
            'ticket_id' => $ticket->id,
            'assignee_id' => $ticket->assignee_id,
            'created_at' => fake()->dateTimeBetween($ticket->created_at),
        ]);
    }

    // STATES //////////////////////////////////////////////////////////////////////////////////////

    /**
     * Indicate that the order has been shipped.
     */
    public function shipped(): static
    {
        return $this->ofStatus(OrderStatus::Shipped)->approved();
    }

    /**
     * Indicate that the order has been received.
     */
    public function received(): static
    {
        return $this->ofStatus(OrderStatus::Received)->approved();
    }
}
