<?php

namespace App\Models;

use App\Enums\AdjustmentType;
use App\Enums\InvoiceStatus;
use App\Enums\TransactionType;
use App\Models\Concerns\HasDueDate;
use App\Models\Concerns\HasProgress;
use App\Models\Concerns\HasStatus;
use App\Observers\InvoiceObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[ObservedBy([InvoiceObserver::class])]
class Invoice extends Model
{
    /**
     * @use HasFactory<\Database\Factories\InvoiceFactory>
     * @use HasStatus<\App\Enums\InvoiceStatus>
     */
    use HasDueDate, HasFactory, HasProgress, HasStatus;

    /**
     * The model's default values for attributes.
     *
     * @var array
     */
    protected $attributes = [
        'status' => InvoiceStatus::Draft,
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'total',
        'task_total',
        'order_total',
        'discount_amount',
        'fee_amount',
        'compensation_amount',
        'bonus_amount',
        'paid_amount',
        'refunded_amount',
        'due_date',
        'status',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'total' => 'float',
        'task_total' => 'float',
        'order_total' => 'float',
        'discount_amount' => 'float',
        'fee_amount' => 'float',
        'compensation_amount' => 'float',
        'bonus_amount' => 'float',
        'paid_amount' => 'float',
        'refunded_amount' => 'float',
    ];

    // ACCESSORS ///////////////////////////////////////////////////////////////////////////////////

    /**
     * Get the subtotal (task_total + order_total).
     */
    protected function subtotal(): Attribute
    {
        return Attribute::get(
            fn () => ($this->task_total ?? 0) + ($this->order_total ?? 0)
        );
    }

    /**
     * Get the net amount (subtotal + adjustments).
     */
    protected function netAmount(): Attribute
    {
        return Attribute::get(function () {
            $subtotal = $this->subtotal;
            $adjustments = ($this->fee_amount ?? 0)
                - ($this->discount_amount ?? 0)
                - ($this->compensation_amount ?? 0)
                - ($this->bonus_amount ?? 0);

            return $subtotal + $adjustments;
        });
    }

    /**
     * Get the balance (total - paid_amount + refunded_amount).
     */
    protected function balance(): Attribute
    {
        return Attribute::get(
            fn () => ($this->total ?? 0) - ($this->paid_amount ?? 0) + ($this->refunded_amount ?? 0)
        );
    }

    // RELATIONS ///////////////////////////////////////////////////////////////////////////////////

    /**
     * Get the ticket associated with the invoice.
     */
    public function ticket(): BelongsTo
    {
        return $this->belongsTo(Ticket::class);
    }

    /**
     * Get the customer associated with the invoice via ticket.
     */
    public function device(): BelongsTo
    {
        return $this->ticket->device();
    }

    /**
     * Get the customer associated with the invoice via ticket.
     */
    public function customer(): BelongsTo
    {
        return $this->ticket->device->customer();
    }

    /**
     * Get the transactions associated with the invoice.
     */
    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }

    /**
     * Get the adjustments associated with the invoice.
     */
    public function adjustments(): HasMany
    {
        return $this->hasMany(Adjustment::class);
    }

    // SCOPES //////////////////////////////////////////////////////////////////////////////////////

    // METHODS /////////////////////////////////////////////////////////////////////////////////////

    /**
     * Fill the invoice task total based on billable tasks.
     */
    public function fillTaskTotal(): self
    {
        $taskTotal = $this->ticket->tasks()->billable()->sum('cost');

        return $this->forceFill([
            'task_total' => $taskTotal,
        ]);
    }

    /**
     * Fill the invoice order total based on billable orders.
     */
    public function fillOrderTotal(): self
    {
        $orderTotal = $this->ticket->orders()->billable()->sum('cost');

        return $this->forceFill([
            'order_total' => $orderTotal,
        ]);
    }

    /**
     * Fill the invoice adjustment amounts by type.
     */
    public function fillAdjustmentAmounts(): self
    {
        // Calculate totals by type using collection pipeline
        $totals = $this->adjustments
            ->groupBy(fn ($adjustment) => $adjustment->type->value)
            ->mapWithKeys(fn ($adjustments, $typeValue) => [
                $typeValue => $adjustments->sum(fn ($adjustment) => $adjustment->getEffectiveAmount($this->subtotal)),
            ]);

        return $this->forceFill([
            'discount_amount' => abs($totals[AdjustmentType::Discount->value] ?? 0),
            'fee_amount' => $totals[AdjustmentType::Fee->value] ?? 0,
            'compensation_amount' => abs($totals[AdjustmentType::Compensation->value] ?? 0),
            'bonus_amount' => abs($totals[AdjustmentType::Bonus->value] ?? 0),
        ]);
    }

    /**
     * Check if the invoice has percentage-based adjustments.
     * Uses the cached adjustments collection for efficiency.
     */
    public function hasPercentageAdjustments(): bool
    {
        return $this->adjustments->whereNotNull('percentage')->isNotEmpty();
    }

    /**
     * Fill transaction-based amounts for the invoice (paid_amount, refunded_amount).
     */
    public function fillTransactionAmounts(): self
    {
        $transactionAmounts = $this->transactions()
            ->selectRaw('type, COALESCE(SUM(amount), 0) as total_amount')
            ->groupBy('type')
            ->pluck('total_amount', 'type');

        $paidAmount = $transactionAmounts[TransactionType::Payment->value] ?? 0;
        $refundedAmount = $transactionAmounts[TransactionType::Refund->value] ?? 0;

        return $this->forceFill([
            'paid_amount' => $paidAmount,
            'refunded_amount' => $refundedAmount,
        ]);
    }

    /**
     * Sync the total with net_amount (can be overridden manually if needed).
     */
    public function syncTotal(): self
    {
        return $this->forceFill([
            'total' => $this->net_amount,
        ]);
    }

    /**
     * Fill the invoice status based on financial amounts.
     */
    public function fillStatus(): self
    {
        return $this->forceFill([
            'status' => $this->getComputedStatus(),
        ]);
    }

    /**
     * Calculate the appropriate invoice status based on financial amounts.
     */
    private function getComputedStatus(): InvoiceStatus
    {
        // If there are any refunds, status is Refunded
        if ($this->refunded_amount > 0) {
            return InvoiceStatus::Refunded;
        }

        // If paid amount covers the total, status is Paid
        if ($this->paid_amount >= $this->total) {
            return InvoiceStatus::Paid;
        }

        // If there are partial payments, status is Sent
        if ($this->paid_amount > 0) {
            return InvoiceStatus::Sent;
        }

        // If no payments and status was previously Sent/Paid, revert to Draft
        if ($this->paid_amount == 0 && in_array($this->status, [InvoiceStatus::Sent, InvoiceStatus::Paid])) {
            return InvoiceStatus::Draft;
        }

        // Otherwise, keep current status (matches ELSE status in trigger)
        return $this->status;
    }
}
