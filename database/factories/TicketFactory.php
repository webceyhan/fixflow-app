<?php

namespace Database\Factories;

use App\Enums\Priority;
use App\Enums\TicketStatus;
use App\Models\Device;
use Database\Factories\States\HasAssignableStates;
use Database\Factories\States\HasDueDateStates;
use Database\Factories\States\HasPriorityStates;
use Database\Factories\States\HasProgressStates;
use Database\Factories\States\HasStatusStates;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Ticket>
 *
 * @method static hasTasks(int $count = 1, array $attributes = [])
 * @method static hasOrders(int $count = 1, array $attributes = [])
 */
class TicketFactory extends Factory
{
    use HasAssignableStates, HasDueDateStates, HasPriorityStates, HasProgressStates, HasStatusStates;

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
            'priority' => Priority::Medium,
            'status' => TicketStatus::New,
            'due_date' => now()->addWeek(),
        ];
    }

    // RELATIONS ///////////////////////////////////////////////////////////////////////////////////

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
}
