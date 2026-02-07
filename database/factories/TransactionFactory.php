<?php

namespace Database\Factories;

use App\Enums\TransactionMethod;
use App\Enums\TransactionType;
use App\Models\Invoice;
use App\Models\Transaction;
use Database\Factories\States\NoteStates;
use Database\Factories\States\TypeStates;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Transaction>
 */
class TransactionFactory extends Factory
{
    use NoteStates,
        TypeStates;

    private const TRANSACTION_NOTES = [
        TransactionType::Payment->value => [
            'Partial payment received',
            'Final payment for services',
            'Balance payment',
            'Full payment received',
            'Installment payment 1 of 2',
            'Installment payment 2 of 2',
        ],
        TransactionType::Refund->value => [
            'Refund for cancelled service',
            'Partial refund for warranty claim',
            'Full refund issued',
            'Refund for duplicate payment',
        ],
        TransactionType::Deposit->value => [
            'Initial deposit for repair',
            'Advance payment received',
            'Down payment',
            'Security deposit',
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
            'amount' => fake()->randomFloat(2, 10, 500),
            'note' => null,
            'method' => fake()->randomElement(TransactionMethod::cases()),
            'type' => TransactionType::Payment,
        ];
    }

    /**
     * Configure the model factory.
     */
    public function configure(): static
    {
        return $this->afterMaking(function (Transaction $transaction) {
            if (! $transaction->note) {
                $notesByType = self::TRANSACTION_NOTES[$transaction->type->value];
                $transaction->note = fake()->optional(0.6)->randomElement($notesByType);
            }
        });
    }

    // RELATIONS ///////////////////////////////////////////////////////////////////////////////////

    /**
     * Indicate that the transaction is for a specific invoice.
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
     * Set the model to a specific method.
     */
    public function ofMethod(TransactionMethod $method): self
    {
        return $this->state(fn (array $attributes) => [
            'method' => $method,
        ]);
    }

    /**
     * Indicate that the transaction is a deposit.
     */
    public function deposit(float $amount = 0): static
    {
        return $this->state(['amount' => $amount])->ofType(TransactionType::Deposit);
    }

    /**
     * Indicate that the transaction is a payment.
     */
    public function payment(float $amount = 0): static
    {
        return $this->state(['amount' => $amount])->ofType(TransactionType::Payment);
    }

    /**
     * Indicate that the transaction is a refund.
     */
    public function refund(float $amount = 0): static
    {
        return $this->state(['amount' => $amount])->ofType(TransactionType::Refund);
    }
}
