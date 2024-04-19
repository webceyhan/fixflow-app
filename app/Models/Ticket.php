<?php

namespace App\Models;

use App\Enums\Priority;
use App\Enums\TicketStatus;
use App\Models\Concerns\Assignable;
use App\Models\Concerns\Completable;
use App\Models\Concerns\HasPriority;
use Database\Factories\TicketFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int|null $assignee_id
 * @property int $customer_id
 * @property string $description
 * @property Priority $priority
 * @property TicketStatus $status
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property float $total_cost
 * @property-read int $total_tasks_count
 * @property-read int $pending_tasks_count
 * @property-read int $total_orders_count
 * @property-read int $pending_orders_count
 * 
 * @property-read Customer $customer
 * @property-read Device $device
 * @property-read Collection<int, Task> $tasks
 * @property-read Collection<int, Order> $orders
 * @property-read Invoice|null $invoice
 * 
 * @method static TicketFactory factory(int $count = null, array $state = [])
 * @method static Builder|static ofStatus(TicketStatus $status)
 */
class Ticket extends Model
{
    use HasFactory, Assignable, HasPriority, Completable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'description',
        'priority',
        'status',
    ];

    /**
     * The model's default values for attributes.
     *
     * @var array
     */
    protected $attributes = [
        'status' => TicketStatus::New,
        'total_cost' => 0,
        'total_tasks_count' => 0,
        'pending_tasks_count' => 0,
        'total_orders_count' => 0,
        'pending_orders_count' => 0,
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'status' => TicketStatus::class,
            'total_cost' => 'float',
        ];
    }

    // RELATIONS ///////////////////////////////////////////////////////////////////////////////////

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function device(): BelongsTo
    {
        return $this->belongsTo(Device::class);
    }

    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function invoice(): HasOne
    {
        return $this->hasOne(Invoice::class);
    }

    // METHODS /////////////////////////////////////////////////////////////////////////////////////    

    public function fillTotalCost(): static
    {
        return $this->forceFill([
            'total_cost' => 0
                + $this->tasks()->billable()->sum('cost')
                + $this->orders()->billable()->sum('cost')
        ]);
    }

    public function fillTaskCounters(): static
    {
        return $this->forceFill([
            'total_tasks_count' => $this->tasks()->count(),
            'pending_tasks_count' => $this->tasks()->pending()->count(),
        ]);
    }

    public function fillOrderCounters(): static
    {
        return $this->forceFill([
            'total_orders_count' => $this->orders()->count(),
            'pending_orders_count' => $this->orders()->pending()->count(),
        ]);
    }

    // SCOPES //////////////////////////////////////////////////////////////////////////////////////

    /**
     * Scope a query to only include tickets of a given status.
     */
    public function scopeOfStatus(Builder $query, TicketStatus $status): void
    {
        $query->where('status', $status->value);
    }
}
