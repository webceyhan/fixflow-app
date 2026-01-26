<?php

namespace Database\Factories;

use App\Enums\Priority;
use App\Enums\TicketStatus;
use App\Models\Device;
use Database\Factories\States\ActiveStatusStates;
use Database\Factories\States\AssignmentStates;
use Database\Factories\States\DueDateStates;
use Database\Factories\States\NoteStates;
use Database\Factories\States\PriorityStates;
use Database\Factories\States\StatusStates;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Ticket>
 */
class TicketFactory extends Factory
{
    use ActiveStatusStates,
        AssignmentStates,
        DueDateStates,
        NoteStates,
        PriorityStates,
        StatusStates;

    private const ISSUES = [
        'Screen Repair',
        'Battery Replacement',
        'Water Damage',
        'Software Issue',
        'Hardware Issue',
        'Data Recovery',
        'Performance Issue',
        'Connectivity Problem',
    ];

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'device_id' => Device::factory(),
            'assignee_id' => null,
            'title' => fake()->randomElement(self::ISSUES),
            'note' => null,
            'priority' => fake()->randomElement(Priority::cases()),
            'status' => TicketStatus::Created,
            'due_date' => fake()->dateTimeBetween('now', '+2 weeks'),
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
