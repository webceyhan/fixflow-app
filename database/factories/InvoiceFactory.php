<?php

namespace Database\Factories;

use App\Enums\InvoiceStatus;
use App\Models\Ticket;
use Database\Factories\States\HasDueDateStates;
use Database\Factories\States\HasProgressStates;
use Database\Factories\States\HasStatusStates;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Invoice>
 *
 * @method static hasTransactions(int $count = 1, array $attributes = [])
 */
class InvoiceFactory extends Factory
{
    use HasDueDateStates, HasProgressStates, HasStatusStates;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Generate random values for task total, order total, and discount amount
        $taskCostTotal = fake()->randomFloat(2, 50, 100);
        $orderCostTotal = fake()->randomFloat(2, 50, 100);
        $discountAmount = fake()->randomFloat(2, 0, 20);

        // Calculate subtotal as the sum of task total and order total minus discount amount
        $total = $taskCostTotal + $orderCostTotal - $discountAmount;

        return [
            'ticket_id' => Ticket::factory(),
            'total' => $total,
            'task_total' => $taskCostTotal,
            'order_total' => $orderCostTotal,
            'discount_amount' => $discountAmount,
            'paid_amount' => 0,
            'refunded_amount' => 0,
            'due_date' => now()->addWeek(),
            'status' => InvoiceStatus::Draft,
        ];
    }

    // RELATIONS ///////////////////////////////////////////////////////////////////////////////////

    /**
     * Indicate that the invoice belongs to the specified ticket.
     */
    public function forTicket(Ticket $ticket): static
    {
        return $this->state(fn (array $attributes) => [
            'ticket_id' => $ticket->id,
            'created_at' => $ticket->created_at,
            'due_date' => $ticket->created_at->addWeek(),
        ]);
    }

    // STATES //////////////////////////////////////////////////////////////////////////////////////

    /**
     * Indicate that the invoice has been drafted.
     */
    public function draft(): static
    {
        return $this->state(fn (array $attributes) => [
            'total' => 0,
            'task_total' => 0,
            'order_total' => 0,
            'discount_amount' => 0,
            'paid_amount' => 0,
            'refunded_amount' => 0,
            'status' => InvoiceStatus::Draft,
        ]);
    }

    /**
     * Indicate that the invoice has been paid.
     */
    public function paid(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => InvoiceStatus::Paid,
            'paid_amount' => $attributes['total'] ?? 0,
        ]);
    }

    /**
     * Indicate that the invoice has been refunded.
     */
    public function refunded(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => InvoiceStatus::Refunded,
            'refunded_amount' => $attributes['total'] ?? 0,
        ]);
    }
}
