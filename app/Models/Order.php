<?php

namespace App\Models;

use App\Enums\OrderStatus;
use App\Models\Concerns\Approvable;
use App\Models\Concerns\Assignable;
use App\Models\Concerns\Billable;
use App\Models\Concerns\HasActiveStatus;
use App\Models\Concerns\HasCancelledStatus;
use App\Models\Concerns\HasDueDate;
use App\Models\Concerns\HasRef;
use App\Models\Concerns\HasStatus;
use Database\Factories\OrderFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Order extends Model
{
    /** @use HasFactory<OrderFactory> */
    use Approvable,
        Assignable,
        Billable,
        HasActiveStatus,
        HasCancelledStatus,
        HasDueDate,
        HasFactory,
        HasRef,
        HasStatus;

    protected string $refPrefix = 'ORD';

    /**
     * The model's default values for attributes.
     *
     * @var array
     */
    protected $attributes = [
        'quantity' => 1,
        'cost' => 0,
        'status' => OrderStatus::Created,
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'ticket_id',
        'assignee_id',
        'name',
        'url',
        'supplier',
        'quantity',
        'cost',
        'is_billable',
        'due_date',
        'note',
        'status',
        'approved_at',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'quantity' => 'integer',
            'cost' => 'float',
        ];
    }

    // RELATIONSHIPS ///////////////////////////////////////////////////////////////////////////////

    /**
     * Get the ticket that owns the order.
     */
    public function ticket(): BelongsTo
    {
        return $this->belongsTo(Ticket::class);
    }
}
