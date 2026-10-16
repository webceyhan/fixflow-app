<?php

namespace Database\Factories;

use App\Enums\AttachmentCategory;
use App\Models\Attachment;
use App\Models\Ticket;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Attachment>
 */
class AttachmentFactory extends Factory
{
    protected $model = Attachment::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'attachable_type' => Ticket::class,
            'attachable_id' => Ticket::factory(),
            'name' => fake()->words(3, true).'.pdf',
            'path' => 'tickets/'.fake()->numberBetween(1, 999).'/'.fake()->uuid().'.pdf',
            'mime_type' => 'application/pdf',
            'size' => fake()->numberBetween(10_000, 500_000),
            'category' => fake()->randomElement(AttachmentCategory::cases()),
        ];
    }

    /**
     * Indicate that the attachment belongs to a specific ticket.
     */
    public function forTicket(Ticket $ticket): static
    {
        return $this->state(fn (array $attributes) => [
            'attachable_type' => Ticket::class,
            'attachable_id' => $ticket->id,
        ]);
    }
}
