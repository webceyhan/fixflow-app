<?php

namespace Database\Factories;

use App\Enums\InvoiceStatus;
use App\Models\Ticket;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Invoice>
 */
class InvoiceFactory extends Factory
{
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
        $subtotal = $taskCostTotal + $orderCostTotal - $discountAmount;

        // Ensure subtotal is not negative
        $subtotal = max($subtotal, 0);

        // Ensure total is at least equal to subtotal
        $total = max($subtotal, $discountAmount);

        return [
            'ticket_id' => Ticket::factory(),
            'total' => $total,
            'subtotal' => $subtotal,
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
     * Indicate that the invoice is of a specified status.
     */
    public function ofStatus(InvoiceStatus $status): self
    {
        return $this->state(fn (array $attributes) => [
            'status' => $status,
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

    /**
     * Indicate that the invoice is overdue.
     */
    public function overdue(): static
    {
        return $this->state(fn (array $attributes) => [
            'due_date' => now()->subDays(1),
            'status' => InvoiceStatus::Issued,
        ]);
    }
}
