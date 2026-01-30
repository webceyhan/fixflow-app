<?php

namespace Database\Factories;

use App\Enums\OrderStatus;
use App\Models\Ticket;
use Database\Factories\States\ActiveStatusStates;
use Database\Factories\States\ApprovalStates;
use Database\Factories\States\AssignmentStates;
use Database\Factories\States\BillingStates;
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
        DueDateStates,
        NoteStates,
        StatusStates;

    private const ORDER_NAMES = [
        'iPhone 13 Screen Assembly',
        'Samsung Galaxy Battery',
        'MacBook Pro Logic Board',
        'iPad Air Digitizer',
        'USB-C Charging Cable',
        'Phone Case - Clear',
        'Screen Protector Tempered Glass',
        'Replacement Camera Module',
        'Laptop RAM 8GB DDR4',
        'SSD 256GB NVMe',
        'Thermal Paste',
        'Flex Cable Ribbon',
        'Power Button Assembly',
        'Speaker Replacement',
        'Microphone Component',
        'Headphone Jack',
        'Home Button Flex Cable',
        'Back Cover Glass',
        'Front Camera Assembly',
        'Vibration Motor',
    ];

    private const SUPPLIERS = [
        'iFixit',
        'Injured Gadgets',
        'Mobile Defenders',
        'Repairs Universe',
        'Wholesale Gadget Parts',
        'Tech Parts Depot',
        'Global Direct Parts',
        'Mobile Sentrix',
        'Fixez',
        'Gadget Fix',
    ];

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
            'name' => fake()->randomElement(self::ORDER_NAMES),
            'url' => fake()->optional(0.5)->url(),
            'supplier' => fake()->optional(0.7)->randomElement(self::SUPPLIERS),
            'quantity' => fake()->numberBetween(1, 10),
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

    /**
     * Indicate that the order has been cancelled.
     */
    public function cancelled(): static
    {
        return $this->ofStatus(OrderStatus::Cancelled);
    }
}
