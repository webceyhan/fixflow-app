<?php

namespace Database\Factories;

use App\Enums\Priority;
use App\Enums\TaskStatus;
use App\Enums\TaskType;
use App\Models\Ticket;
use Database\Factories\States\ActiveStatusStates;
use Database\Factories\States\ApprovalStates;
use Database\Factories\States\AssignmentStates;
use Database\Factories\States\BillingStates;
use Database\Factories\States\CancelledStatusStates;
use Database\Factories\States\NoteStates;
use Database\Factories\States\PriorityStates;
use Database\Factories\States\StatusStates;
use Database\Factories\States\TypeStates;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Task>
 */
class TaskFactory extends Factory
{
    use ActiveStatusStates,
        ApprovalStates,
        AssignmentStates,
        BillingStates,
        CancelledStatusStates,
        NoteStates,
        PriorityStates,
        StatusStates,
        TypeStates;

    private const TASK_NOTES = [
        TaskType::Repair->value => [
            'Replaced cracked screen with OEM part',
            'Fixed broken charging port',
            'Repaired camera module connection',
            'Replaced faulty speaker',
        ],
        TaskType::Diagnosis->value => [
            'Performed full hardware diagnostic',
            'Tested all components for functionality',
            'Identified water damage on motherboard',
            'Found battery capacity degraded to 60%',
        ],
        TaskType::Testing->value => [
            'Verified all functions after repair',
            'Tested device for 24 hours',
            'Quality check completed successfully',
            'Stress tested all components',
        ],
        TaskType::Calibration->value => [
            'Calibrated touchscreen sensitivity',
            'Adjusted display color profile',
            'Recalibrated battery meter',
            'Aligned camera sensors',
        ],
        TaskType::Installation->value => [
            'Installed latest firmware update',
            'Set up device with customer preferences',
            'Installed security patches',
            'Configured backup settings',
        ],
        TaskType::Upgrade->value => [
            'Upgraded RAM from 4GB to 8GB',
            'Replaced HDD with SSD',
            'Updated to latest OS version',
            'Increased storage capacity',
        ],
        TaskType::Maintenance->value => [
            'Cleaned internal components',
            'Applied thermal paste',
            'Removed dust and debris',
            'Checked and tightened all connections',
        ],
        TaskType::Cleaning->value => [
            'Deep cleaned device exterior',
            'Sanitized all surfaces',
            'Cleaned charging port',
            'Removed adhesive residue',
        ],
        TaskType::Other->value => [
            'Customer consultation completed',
            'Parts ordered for future repair',
            'Provided technical support',
            'Documentation updated',
        ],
    ];

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $type = fake()->randomElement(TaskType::cases());

        return [
            'ticket_id' => Ticket::factory(),
            'assignee_id' => null,
            'cost' => fake()->randomFloat(2, 10, 500),
            'is_billable' => fake()->boolean(80),
            'note' => fake()->randomElement(self::TASK_NOTES[$type->value]),
            'priority' => fake()->randomElement(Priority::cases()),
            'type' => $type,
            'status' => TaskStatus::Created,
            'approved_at' => null,
        ];
    }

    // RELATIONS ///////////////////////////////////////////////////////////////////////////////////

    /**
     * Indicate that the task belongs to a specific ticket.
     */
    public function forTicket(Ticket $ticket): self
    {
        return $this->state(fn (array $attributes) => [
            'ticket_id' => $ticket->id,
            'assignee_id' => $ticket->assignee_id,
            'priority' => $ticket->priority,
            'created_at' => fake()->dateTimeBetween($ticket->created_at),
        ]);
    }

    // STATES //////////////////////////////////////////////////////////////////////////////////////

    /**
     * Indicate that the task is completed.
     */
    public function completed(): self
    {
        return $this->ofStatus(TaskStatus::Completed)->approved();
    }
}
