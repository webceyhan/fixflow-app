<?php

namespace Database\Factories;

use App\Enums\TransactionMethod;
use App\Enums\TransactionType;
use App\Models\Invoice;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Transaction>
 */
class TransactionFactory extends Factory
{
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

    public function forInvoice(Invoice $invoice): static
    {
        return $this->state(fn (array $attributes) => [
            'invoice_id' => $invoice->id,
        ]);
    }

    // STATES //////////////////////////////////////////////////////////////////////////////////////

    /**
     * Indicate that the transaction has no note.
     */
    public function withoutNote(): static
    {
        return $this->state(fn (array $attributes) => [
            'note' => null,
        ]);
    }

    /**
     * Indicate that the transaction is of a specified method.
     */
    public function ofMethod(TransactionMethod $method): static
    {
        return $this->state(fn (array $attributes) => [
            'method' => $method,
        ]);
    }

    /**
     * Indicate that the transaction is of a specified type.
     */
    public function ofType(TransactionType $type): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => $type,
        ]);
    }

    /**
     * Indicate that the transaction is a refund.
     */
    public function refund(): static
    {
        return $this->ofType(TransactionType::Refund);
    }
}
