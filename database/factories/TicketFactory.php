<?php

namespace Database\Factories;

use App\Enums\TicketPriority;
use App\Enums\TicketStatus;
use App\Models\Device;
use App\Models\User;
use Database\Factories\States\HasProgressStates;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Ticket>
 *
 * @method static hasTasks(int $count = 1, array $attributes = [])
 * @method static hasOrders(int $count = 1, array $attributes = [])
 */
class TicketFactory extends Factory
{
    use HasProgressStates;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'device_id' => Device::factory(),
            'title' => fake()->sentence(),
            'description' => fake()->paragraph(),
            'priority' => TicketPriority::Normal,
            'status' => TicketStatus::New,
            'due_date' => now()->addWeek(),
        ];
    }

    // RELATIONS ///////////////////////////////////////////////////////////////////////////////////

    /**
     * Indicate that the ticket is assigned to a specific user.
     */
    public function forAssignee(User $user): self
    {
        return $this->state(fn (array $attributes) => [
            'assignee_id' => $user->id,
        ]);
    }

    /**
     * Indicate that the ticket belongs to a specific device.
     */
    public function forDevice(Device $device): self
    {
        return $this->state(fn (array $attributes) => [
            'device_id' => $device->id,
            'created_at' => $device->created_at,
            'due_date' => $device->created_at->addWeek(),
        ]);
    }

    // STATES //////////////////////////////////////////////////////////////////////////////////////

    /**
     * Indicate that the ticket is of the given priority.
     */
    public function ofPriority(TicketPriority $priority): self
    {
        return $this->state(fn (array $attributes) => [
            'priority' => $priority,
        ]);
    }

    /**
     * Indicate that the ticket is of the given status.
     */
    public function ofStatus(TicketStatus $status): self
    {
        return $this->state(fn (array $attributes) => [
            'status' => $status,
        ]);
    }

    /**
     * Indicate that the ticket is overdue.
     */
    public function overdue(): self
    {
        return $this->state(fn (array $attributes) => [
            'due_date' => now()->subDay(),
            'status' => TicketStatus::New,
        ]);
    }
}
