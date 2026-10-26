<?php

namespace App\Models;

use App\Enums\Priority;
use App\Enums\TicketStatus;
use App\Models\Concerns\Assignable;
use App\Models\Concerns\HasActiveStatus;
use App\Models\Concerns\HasCancelledStatus;
use App\Models\Concerns\HasDueDate;
use App\Models\Concerns\HasPriority;
use App\Models\Concerns\HasRef;
use App\Models\Concerns\HasStatus;
use Carbon\CarbonImmutable;
use Database\Factories\TicketFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Illuminate\Database\Eloquent\Relations\MorphMany;

/**
 * @property int $id
 * @property int $device_id
 * @property int|null $assignee_id
 * @property string $title
 * @property string|null $note
 * @property Priority $priority
 * @property TicketStatus $status
 * @property CarbonImmutable|null $due_date
 * @property CarbonImmutable|null $created_at
 * @property CarbonImmutable|null $updated_at
 * @property-read User|null $assignee
 * @property-read Collection<int, Attachment> $attachments
 * @property-read int|null $attachments_count
 * @property-read Customer|null $customer
 * @property-read Device $device
 * @property-read Invoice|null $invoice
 * @property-read Collection<int, Order> $orders
 * @property-read int|null $orders_count
 * @property-read mixed $ref
 * @property-read Collection<int, Task> $tasks
 * @property-read int|null $tasks_count
 */
#[Fillable(['device_id', 'assignee_id', 'title', 'note', 'priority', 'status', 'due_date'])]
class Ticket extends Model
{
    /**
     * @use Assignable<$this>
     * @use HasActiveStatus<static>
     * @use HasCancelledStatus<static>
     * @use HasDueDate<static>
     * @use HasPriority<static, Priority>
     * @use HasFactory<TicketFactory>
     * @use HasRef<static>
     * @use HasStatus<static, TicketStatus>
     */
    use Assignable,
        HasActiveStatus,
        HasCancelledStatus,
        HasDueDate,
        HasFactory,
        HasPriority,
        HasRef,
        HasStatus;

    protected string $refPrefix = 'TKT';

    /**
     * The model's default values for attributes.
     *
     * @var array<string, mixed>
     */
    protected $attributes = [
        'priority' => Priority::Medium,
        'status' => TicketStatus::Created,
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [];
    }

    // RELATIONSHIPS ///////////////////////////////////////////////////////////////////////////////

    /**
     * Get the device that owns the ticket.
     *
     * @return BelongsTo<Device, $this>
     */
    public function device(): BelongsTo
    {
        return $this->belongsTo(Device::class);
    }

    /**
     * Get the customer for the ticket via the device.
     *
     * @return HasOneThrough<Customer, Device, $this>
     */
    public function customer(): HasOneThrough
    {
        return $this->hasOneThrough(Customer::class, Device::class, 'id', 'id', 'device_id', 'customer_id');
    }

    /**
     * Get the tasks for the ticket.
     *
     * @return HasMany<Task, $this>
     */
    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class);
    }

    /**
     * Get the orders for the ticket.
     *
     * @return HasMany<Order, $this>
     */
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    /**
     * Get the file attachments for the ticket.
     *
     * @return MorphMany<Attachment, $this>
     */
    public function attachments(): MorphMany
    {
        return $this->morphMany(Attachment::class, 'attachable');
    }

    /**
     * Get the invoice for the ticket.
     *
     * @return HasOne<Invoice, $this>
     */
    public function invoice(): HasOne
    {
        return $this->hasOne(Invoice::class);
    }

    // SCOPES //////////////////////////////////////////////////////////////////////////////////////

    // METHODS /////////////////////////////////////////////////////////////////////////////////////
}
