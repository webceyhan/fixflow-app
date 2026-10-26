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
use Carbon\CarbonImmutable;
use Database\Factories\OrderFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $ticket_id
 * @property int|null $assignee_id
 * @property string $name
 * @property string|null $url
 * @property string|null $supplier
 * @property int $quantity
 * @property float $cost
 * @property bool $is_billable
 * @property CarbonImmutable|null $due_date
 * @property string|null $note
 * @property OrderStatus $status
 * @property CarbonImmutable|null $created_at
 * @property CarbonImmutable|null $updated_at
 * @property CarbonImmutable|null $approved_at
 * @property-read User|null $assignee
 * @property-read mixed $ref
 * @property-read Ticket $ticket
 */
#[Fillable(['ticket_id', 'assignee_id', 'name', 'url', 'supplier', 'quantity', 'cost', 'is_billable', 'due_date', 'note', 'status', 'approved_at'])]
class Order extends Model
{
    /**
     * @use Approvable<static>
     * @use Assignable<$this>
     * @use Billable<static>
     * @use HasActiveStatus<static>
     * @use HasCancelledStatus<static>
     * @use HasDueDate<static>
     * @use HasFactory<OrderFactory>
     * @use HasRef<static>
     * @use HasStatus<static, OrderStatus>
     */
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
     * @var array<string, mixed>
     */
    protected $attributes = [
        'quantity' => 1,
        'cost' => 0,
        'status' => OrderStatus::Created,
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
     *
     * @return BelongsTo<Ticket, $this>
     */
    public function ticket(): BelongsTo
    {
        return $this->belongsTo(Ticket::class);
    }
}
