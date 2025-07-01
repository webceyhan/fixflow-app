<?php

namespace Database\Factories;

use App\Enums\OrderStatus;
use App\Models\Ticket;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{
    const PARTS = [
        'Dell XPS 13 Battery',
        'MacBook Pro Retina Screen',
        'Lenovo ThinkPad Keyboard',
        'HP Envy Touchpad',
        'Intel Core i7-10700K CPU',
        'ASUS ROG Strix Z490-E Motherboard',
        'Corsair Vengeance 16GB DDR4 RAM',
        'Western Digital 1TB SSD',
        'EVGA 600W Power Supply',
        'NVIDIA GeForce GTX 1660 Super Graphics Card',
        'iPhone 12 Screen Replacement',
        'Samsung Galaxy S20 Battery',
        'iPhone XR Charging Port',
        'Google Pixel 5 Camera',
        'iPad Pro 11" Screen',
        'Samsung Galaxy Tab S6 Battery',
        'iPad Air Charging Port',
        'Microsoft Surface Pro Camera',
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
            'name' => fake()->randomElement(self::PARTS),
            'url' => fake()->url(),
            'supplier' => fake()->company(),
            'quantity' => rand(1, 2),
            'cost' => fake()->randomFloat(2, 10, 100),
            'is_billable' => true,
            'status' => OrderStatus::New,
            'approved_at' => now(),
        ];
    }

    // RELATIONS ///////////////////////////////////////////////////////////////////////////////////

    /**
     * Indicate that the order belongs to the specified ticket.
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
     * Indicate that the order is named as the given part.
     */
    public function namedAs(string $name): self
    {
        return $this->state(fn (array $attributes) => [
            'name' => $name,
        ]);
    }

    /**
     * Indicate that the order is supplied by the given supplier.
     */
    public function suppliedBy(string $supplier): self
    {
        return $this->state(fn (array $attributes) => [
            'supplier' => $supplier,
        ]);
    }

    /**
     * Indicate that the order is non-billable.
     */
    public function nonBillable(): self
    {
        return $this->state(fn (array $attributes) => [
            'is_billable' => false,
        ]);
    }

    /**
     * Indicate that the order is of a specified status.
     */
    public function ofStatus(OrderStatus $status): self
    {
        return $this->state(fn (array $attributes) => [
            'status' => $status,
        ]);
    }

    /**
     * Indicate that the order needs customer approval.
     */
    public function needsApproval(): self
    {
        return $this->state(fn (array $attributes) => [
            'approved_at' => null,
        ]);
    }
}
