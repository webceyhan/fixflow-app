<?php

namespace App\Models;

use App\Enums\InvoiceStatus;
use App\Models\Concerns\HasActiveStatus;
use App\Models\Concerns\HasDueDate;
use App\Models\Concerns\HasStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Invoice extends Model
{
    /** @use HasFactory<\Database\Factories\InvoiceFactory> */
    use HasActiveStatus,
        HasDueDate,
        HasFactory,
        HasStatus;

    /**
     * The model's default values for attributes.
     *
     * @var array
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
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'ticket_id',
        'total',
        'task_total',
        'order_total',
        'discount_amount',
        'fee_amount',
        'paid_amount',
        'refunded_amount',
        'due_date',
        'note',
        'status',
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
     */
    public function ticket(): BelongsTo
    {
        return $this->belongsTo(Ticket::class);
    }

    /**
     * Get the transactions for the invoice.
     */
    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }

    /**
     * Get the adjustments for the invoice.
     */
    public function adjustments(): HasMany
    {
        return $this->hasMany(Adjustment::class);
    }
}
