<?php

namespace Database\Factories;

use App\Enums\AdjustmentReason;
use App\Enums\AdjustmentType;
use App\Models\Invoice;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Adjustment>
 */
class AdjustmentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $reason = fake()->randomElement(AdjustmentReason::cases());

        return [
            'invoice_id' => Invoice::factory(),
            'amount' => fake()->randomFloat(2, 5, 100),
            'percentage' => $reason->percentage(),
            'note' => fake()->optional(0.3)->sentence(),
            'type' => $reason->type(),
            'reason' => $reason,
        ];
    }

    // RELATIONS ///////////////////////////////////////////////////////////////////////////////////

    /**
     * Indicate that the adjustment belongs to the given invoice.
     */
    public function forInvoice(Invoice $invoice): self
    {
        return $this->state(fn () => [
            'invoice_id' => $invoice->id,
            'created_at' => fake()->dateTimeBetween($invoice->created_at),
        ]);
    }

    // STATES //////////////////////////////////////////////////////////////////////////////////////

    /**
     * Indicate that the adjustment has no note.
     */
    public function withoutNote(): self
    {
        return $this->state(fn (array $attributes) => [
            'note' => null,
        ]);
    }

    /**
     * Indicate that the adjustment is of a specified type.
     */
    public function ofType(AdjustmentType $type): self
    {
        return $this->state(fn (array $attributes) => [
            'type' => $type,
        ]);
    }

    /**
     * Indicate that the adjustment is of a specific reason.
     */
    public function ofReason(AdjustmentReason $reason): self
    {
        return $this->state(fn (array $attributes) => [
            'reason' => $reason,
            'type' => $reason->type(),
            'percentage' => $reason->percentage(),
        ]);
    }

    /**
     * Indicate that the adjustment has a fixed amount with proper signing.
     */
    public function withAmount(?float $amount = null): self
    {
        return $this->state(fn (array $attributes) => [
            'amount' => $amount ?? fake()->randomFloat(2, 5, 100),
            'percentage' => null,
        ]);
    }

    /**
     * Indicate that the adjustment has a percentage amount.
     */
    public function withPercentage(?float $percentage = null): self
    {
        return $this->state(fn (array $attributes) => [
            'amount' => 0,
            'percentage' => $percentage ?? fake()->randomFloat(2, 5, 100),
        ]);
    }
}
