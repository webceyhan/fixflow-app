<?php

namespace App\Models;

use App\Enums\OrderStatus;
use App\Models\Concerns\Billable;
use App\Models\Concerns\HasProgress;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    /** @use HasFactory<\Database\Factories\OrderFactory> */
    use Billable, HasFactory, HasProgress;

    /**
     * The model's default values for attributes.
     *
     * @var array
     */
    protected $attributes = [
        'quantity' => 1,
        'status' => OrderStatus::New,
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'url',
        'supplier',
        'quantity',
        'cost',
        'is_billable',
        'status',
        'approved_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'quantity' => 'integer',
        'cost' => 'float',
        'status' => OrderStatus::class,
        'approved_at' => 'datetime',
    ];

    // RELATIONS ///////////////////////////////////////////////////////////////////////////////////

    /**
     * Get the ticket that the order belongs to.
     */
    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }

    // SCOPES //////////////////////////////////////////////////////////////////////////////////////

    /**
     * Scope a query to only include orders of a given status.
     */
    public function scopeOfStatus(Builder $query, OrderStatus $status): void
    {
        $query->where('status', $status->value);
    }

    /**
     * Scope a query to only include approved orders.
     */
    public function scopeApproved(Builder $query): void
    {
        $query->whereNotNull('approved_at');
    }
}
