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
    use NoteStates, TypeStates;

    private const FEE_REASONS = [
        AdjustmentReason::RushService,
        AdjustmentReason::Delivery,
        AdjustmentReason::LatePayment,
        AdjustmentReason::Cancellation,
    ];

    private const DISCOUNT_REASONS = [
        AdjustmentReason::BulkService,
        AdjustmentReason::Promotion,
        AdjustmentReason::Loyalty,
        AdjustmentReason::Welcome,
        AdjustmentReason::Compensation,
    ];

    private const ADJUSTMENT_NOTES = [
        AdjustmentReason::BulkService->value => [
            'Multiple device repair discount',
            'Bundle service discount applied',
            'Volume discount - 3+ services',
        ],
        AdjustmentReason::Promotion->value => [
            'New Year promotion - 15% off',
            'Black Friday special discount',
            'Summer sale discount',
            'Grand opening special',
        ],
        AdjustmentReason::Loyalty->value => [
            'Returning customer discount',
            'VIP customer loyalty reward',
            'Valued customer appreciation',
        ],
        AdjustmentReason::Welcome->value => [
            'First-time customer welcome bonus',
            'New customer special offer',
            'Welcome to our service',
        ],
        AdjustmentReason::Compensation->value => [
            'Delay compensation - service took 2 extra days',
            'Quality issue compensation',
            'Service recovery discount',
            'Manager discretion - customer satisfaction',
        ],
        AdjustmentReason::RushService->value => [
            'Same-day service rush fee',
            'Priority repair surcharge',
            'Express service fee',
        ],
        AdjustmentReason::Delivery->value => [
            'Delivery and pickup fee',
            'Parts shipping charge',
            'Transportation surcharge',
        ],
        AdjustmentReason::LatePayment->value => [
            'Late payment penalty',
            'Overdue invoice fee',
            'Payment delay charge',
        ],
        AdjustmentReason::Cancellation->value => [
            'Cancellation fee - parts ordered',
            'Service cancellation charge',
            'Restocking fee',
        ],
    ];

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

            if (! $adjustment->note) {
                $notesByType = self::ADJUSTMENT_NOTES[$adjustment->reason->value];
                $adjustment->note = fake()->optional(0.6)->randomElement($notesByType);
            }
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
        return $this->state(fn (array $attributes) => [
            'type' => AdjustmentType::Discount,
            'reason' => fake()->randomElement(self::DISCOUNT_REASONS),
        ]);
    }

    /**
     * Indicate that the adjustment is a fee.
     */
    public function fee(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => AdjustmentType::Fee,
            'reason' => fake()->randomElement(self::FEE_REASONS),
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
