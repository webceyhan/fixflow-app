<?php

namespace Database\Factories;

use App\Enums\AdjustmentReason;
use App\Enums\AdjustmentType;
use App\Models\Adjustment;
use App\Models\Invoice;
use Database\Factories\States\NoteStates;
use Database\Factories\States\TypeStates;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Adjustment>
 */
class AdjustmentFactory extends Factory
{
    use NoteStates,
        TypeStates;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'invoice_id' => Invoice::factory(),
            'amount' => fake()->randomFloat(2, 10, 50), // $10-$50 or 10%-50%
            'is_percent' => fake()->boolean(40), // 40% chance of percentage-based
            'note' => null,
            'type' => AdjustmentType::Discount, // may be overridden in configure()
            'reason' => fake()->randomElement(AdjustmentReason::cases()),
        ];
    }

    /**
     * Configure the model factory.
     */
    public function configure(): static
    {
        return $this->afterMaking(function (Adjustment $adjustment) {
            // Ensure consistency between reason and type
            $adjustment->type = $adjustment->reason->type();
        });
    }

    // RELATIONS ///////////////////////////////////////////////////////////////////////////////////

    /**
     * Indicate that the adjustment is for a specific invoice.
     */
    public function forInvoice(Invoice $invoice): static
    {
        return $this->state(fn (array $attributes) => [
            'invoice_id' => $invoice->id,
            'created_at' => fake()->dateTimeBetween($invoice->created_at),
        ]);
    }

    // STATES //////////////////////////////////////////////////////////////////////////////////////

    /**
     * Indicate that the adjustment is a discount.
     */
    public function discount(): static
    {
        $discountReasons = AdjustmentReason::casesByType(AdjustmentType::Discount);

        return $this->state(fn (array $attributes) => [
            'type' => AdjustmentType::Discount,
            'reason' => fake()->randomElement($discountReasons),
        ]);
    }

    /**
     * Indicate that the adjustment is a fee.
     */
    public function fee(): static
    {
        $feeReasons = AdjustmentReason::casesByType(AdjustmentType::Fee);

        return $this->state(fn (array $attributes) => [
            'type' => AdjustmentType::Fee,
            'reason' => fake()->randomElement($feeReasons),
        ]);
    }

    /**
     * Indicate that the adjustment is percentage-based.
     */
    public function percentage(?float $amount = null): static
    {
        return $this->state(fn (array $attributes) => [
            'amount' => $amount ?? fake()->randomFloat(2, 5, 25),
            'is_percent' => true,
        ]);
    }

    /**
     * Indicate that the adjustment is fixed amount.
     */
    public function fixed(?float $amount = null): static
    {
        return $this->state(fn (array $attributes) => [
            'amount' => $amount ?? fake()->randomFloat(2, 10, 100),
            'is_percent' => false,
        ]);
    }
}
