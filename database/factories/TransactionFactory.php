<?php

namespace Database\Factories;

use App\Enums\TransactionMethod;
use App\Enums\TransactionType;
use App\Models\Invoice;
use Database\Factories\States\HasTypeStates;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Transaction>
 */
class TransactionFactory extends Factory
{
    use HasTypeStates;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'invoice_id' => Invoice::factory(),
            'amount' => fake()->randomFloat(2, 10, 100),
            'note' => fake()->sentence(),
            'method' => TransactionMethod::Cash,
            'type' => TransactionType::Payment,
        ];
    }

    // RELATIONS ///////////////////////////////////////////////////////////////////////////////////

    /**
     * Indicate that the transaction belongs to the specified invoice.
     */
    public function forInvoice(Invoice $invoice): self
    {
        return $this->state(fn (array $attributes) => [
            'invoice_id' => $invoice->id,
            'created_at' => $invoice->created_at,
        ]);
    }

    // STATES //////////////////////////////////////////////////////////////////////////////////////

    /**
     * Indicate that the transaction has no note.
     */
    public function withoutNote(): self
    {
        return $this->state(fn (array $attributes) => [
            'note' => null,
        ]);
    }

    /**
     * Indicate that the transaction is of a specified method.
     */
    public function ofMethod(TransactionMethod $method): self
    {
        return $this->state(fn (array $attributes) => [
            'method' => $method,
        ]);
    }

    /**
     * Indicate that the transaction is of a specified type.
     */
    public function ofType(TransactionType $type): self
    {
        return $this->state(fn (array $attributes) => [
            'type' => $type,
        ]);
    }

    /**
     * Indicate that the transaction is a refund.
     */
    public function refund(): self
    {
        return $this->ofType(TransactionType::Refund);
    }

    /**
     * Indicate that the transaction is an adjustment.
     */
    public function adjustment(): self
    {
        return $this->ofType(TransactionType::Adjustment);
    }
}
