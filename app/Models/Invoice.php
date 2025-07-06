<?php

namespace App\Models;

use App\Enums\InvoiceStatus;
use App\Models\Concerns\HasProgress;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Invoice extends Model
{
    /** @use HasFactory<\Database\Factories\InvoiceFactory> */
    use HasFactory, HasProgress;

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
        'subtotal',
        'discount_amount',
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
        'subtotal' => 'float',
        'discount_amount' => 'float',
        'paid_amount' => 'float',
        'refunded_amount' => 'float',
        'due_date' => 'date',
        'status' => InvoiceStatus::class,
    ];

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

    // SCOPES //////////////////////////////////////////////////////////////////////////////////////

    /**
     * Scope a query to only include invoices with the specified status.
     */
    public function scopeOfStatus(Builder $query, InvoiceStatus $status): void
    {
        $query->where('status', $status->value);
    }

    /**
     * Scope a query to only include tickets that are overdue.
     */
    public function scopeOverdue(Builder $query): void
    {
        $query->where('due_date', '<', now())->pending();
    }

    /**
     * Determine if the invoice is overdue.
     */
    public function isOverdue(): bool
    {
        return $this->due_date->isPast() && $this->isPending();
    }
}
