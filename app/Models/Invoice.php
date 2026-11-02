<?php

namespace App\Models;

use App\Enums\InvoiceStatus;
use App\Models\Concerns\HasActiveStatus;
use App\Models\Concerns\HasCancelledStatus;
use App\Models\Concerns\HasDueDate;
use App\Models\Concerns\HasRef;
use App\Models\Concerns\HasStatus;
use Carbon\CarbonImmutable;
use Database\Factories\InvoiceFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property int $ticket_id
 * @property float $total
 * @property float $task_total
 * @property float $order_total
 * @property float $discount_amount
 * @property float $fee_amount
 * @property float $paid_amount
 * @property float $refunded_amount
 * @property CarbonImmutable $due_date
 * @property string|null $note
 * @property InvoiceStatus $status
 * @property CarbonImmutable|null $created_at
 * @property CarbonImmutable|null $updated_at
 * @property-read Collection<int, Adjustment> $adjustments
 * @property-read int|null $adjustments_count
 * @property-read mixed $ref
 * @property-read Ticket $ticket
 * @property-read Collection<int, Transaction> $transactions
 * @property-read int|null $transactions_count
 */
#[Fillable(['ticket_id', 'total', 'task_total', 'order_total', 'discount_amount', 'fee_amount', 'paid_amount', 'refunded_amount', 'due_date', 'note', 'status'])]
class Invoice extends Model
{
    /**
     * @use HasActiveStatus<static>
     * @use HasCancelledStatus<static>
     * @use HasDueDate<static>
     * @use HasFactory<InvoiceFactory>
     * @use HasRef<static>
     * @use HasStatus<static, InvoiceStatus>
     */
    use HasActiveStatus,
        HasCancelledStatus,
        HasDueDate,
        HasFactory,
        HasRef,
        HasStatus;

    protected string $refPrefix = 'INV';

    /**
     * The model's default values for attributes.
     *
     * @var array<string, mixed>
     */
    protected $attributes = [
        'total' => 0,
        'task_total' => 0,
        'order_total' => 0,
        'discount_amount' => 0,
        'fee_amount' => 0,
        'paid_amount' => 0,
        'refunded_amount' => 0,
        'status' => InvoiceStatus::Draft,
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'total' => 'float',
            'task_total' => 'float',
            'order_total' => 'float',
            'discount_amount' => 'float',
            'fee_amount' => 'float',
            'paid_amount' => 'float',
            'refunded_amount' => 'float',
        ];
    }

    // RELATIONSHIPS ///////////////////////////////////////////////////////////////////////////////

    /**
     * Get the ticket that owns the invoice.
     *
     * @return BelongsTo<Ticket, $this>
     */
    public function ticket(): BelongsTo
    {
        return $this->belongsTo(Ticket::class);
    }

    /**
     * Get the transactions for the invoice.
     *
     * @return HasMany<Transaction, $this>
     */
    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }

    /**
     * Get the adjustments for the invoice.
     *
     * @return HasMany<Adjustment, $this>
     */
    public function adjustments(): HasMany
    {
        return $this->hasMany(Adjustment::class);
    }
}
