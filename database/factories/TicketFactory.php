<?php

namespace Database\Factories;

use App\Enums\Priority;
use App\Enums\TicketStatus;
use App\Models\Customer;
use App\Models\Device;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Ticket>
 * 
 * @method static hasTasks(int $count = 1, array $attributes = [])
 * @method static hasOrders(int $count = 1, array $attributes = [])
 * @method static hasInvoice(array $attributes = [])
 */
class TicketFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'customer_id' => Customer::factory(),
            'device_id' => Device::factory(),
            'description' => fake()->paragraph(),
            'priority' => Priority::Normal,
            'status' => TicketStatus::New,
            'total_cost' => 0,
        ];
    }

    // RELATIONS ///////////////////////////////////////////////////////////////////////////////////

    /**
     * Indicate that the ticket is assigned to the given user.
     */
    public function forAssignee(User $user): static
    {
        return $this->state(fn (array $attributes) => [
            'assignee_id' => $user->id,
        ]);
    }

    /**
     * Indicate that the ticket is for the given customer.
     */
    public function forCustomer(Customer $customer): static
    {
        return $this->state(fn (array $attributes) => [
            'customer_id' => $customer->id,
        ]);
    }

    /**
     * Indicate that the ticket is for the given device.
     */
    public function forDevice(Device $device): static
    {
        return $this->state(fn (array $attributes) => [
            'device_id' => $device->id,
            'customer_id' => $device->customer_id,
        ]);
    }

    // STATES //////////////////////////////////////////////////////////////////////////////////////

    /**
     * Indicate that the ticket is assigned to a user.
     */
    public function assigned(): static
    {
        return $this->forAssignee(User::factory()->create());
    }

    /**
     * Indicate that the ticket is of the given priority.
     */
    public function ofPriority(Priority $priority): static
    {
        return $this->state(fn (array $attributes) => [
            'priority' => $priority,
        ]);
    }

    /**
     * Indicate that the ticket is of the given status.
     */
    public function ofStatus(TicketStatus $status): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => $status,
        ]);
    }
}
