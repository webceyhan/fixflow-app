<?php

namespace Database\Factories;

use App\Enums\InvoiceStatus;
use App\Models\Invoice;
use App\Models\Ticket;
use Database\Factories\States\ActiveStatusStates;
use Database\Factories\States\CancelledStatusStates;
use Database\Factories\States\DueDateStates;
use Database\Factories\States\NoteStates;
use Database\Factories\States\StatusStates;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Invoice>
 */
class InvoiceFactory extends Factory
{
    use ActiveStatusStates,
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
        $taskTotal = fake()->randomFloat(2, 0, 500);
        $orderTotal = fake()->randomFloat(2, 0, 300);
        $subtotal = $taskTotal + $orderTotal;

        // Apply a discount of 0%-20% and/or a fee of $0-$50, with some randomness
        $discountAmount = fake()->optional(0.3, 0)->randomFloat(2, 0, $subtotal * 0.2);
        $feeAmount = fake()->optional(0.2, 0)->randomFloat(2, 0, 50);

        $total = $subtotal - $discountAmount + $feeAmount;

        return [
            'ticket_id' => Ticket::factory(),
            'total' => $total,
            'task_total' => $taskTotal,
            'order_total' => $orderTotal,
            'discount_amount' => $discountAmount,
            'fee_amount' => $feeAmount,
            'due_date' => fake()->dateTimeBetween('now', '+30 days'),
            'note' => null,
            'status' => InvoiceStatus::Draft,
        ];
    }

    // RELATIONS ///////////////////////////////////////////////////////////////////////////////////

    /**
     * Indicate that the invoice belongs to a specific ticket.
     */
    public function forTicket(Ticket $ticket): static
    {
        return $this->state(fn (array $attributes) => [
            'ticket_id' => $ticket->id,
            'created_at' => fake()->dateTimeBetween($ticket->created_at),
        ]);
    }

    // STATES //////////////////////////////////////////////////////////////////////////////////////

    /**
     * Indicate that the invoice is in draft status.
     */
    public function draft(): static
    {
        return $this->ofStatus(InvoiceStatus::Draft);
    }

    /**
     * Indicate that the invoice has been issued.
     */
    public function issued(): static
    {
        return $this->ofStatus(InvoiceStatus::Issued);
    }

    /**
     * Indicate that the invoice has been sent.
     */
    public function sent(): static
    {
        return $this->ofStatus(InvoiceStatus::Sent);
    }

    /**
     * Indicate that the invoice has been paid.
     */
    public function paid(): static
    {
        return $this
            ->ofStatus(InvoiceStatus::Paid)
            ->state(fn (array $attributes) => [
                'paid_amount' => $attributes['total'],
            ]);
    }

    /**
     * Indicate that the invoice has been refunded.
     */
    public function refunded(): static
    {
        return $this
            ->ofStatus(InvoiceStatus::Refunded)
            ->state(fn (array $attributes) => [
                'paid_amount' => $attributes['total'],
                'refunded_amount' => $attributes['total'],
            ]);
    }
}
