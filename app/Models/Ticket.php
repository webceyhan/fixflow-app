<?php

namespace App\Models;

use App\Enums\Priority;
use App\Enums\TicketStatus;
use Database\Factories\TicketFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
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
 * 
 * @property-read User|null $assignee
 * @property-read Customer $customer
 * 
 * @method static TicketFactory factory(int $count = null, array $state = [])
 * @method static Builder|static assignable()
 * @method static Builder|static assigned()
 * @method static Builder|static ofPriority(Priority $priority)
 * @method static Builder|static prioritized()
 * @method static Builder|static ofStatus(TicketStatus $status)
 */
class Ticket extends Model
{
    use HasFactory;

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
        'priority' => Priority::Normal,
        'status' => TicketStatus::New,
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'priority' => Priority::class,
            'status' => TicketStatus::class,
        ];
    }

    // RELATIONS ///////////////////////////////////////////////////////////////////////////////////

    public function assignee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assignee_id');
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    // METHODS /////////////////////////////////////////////////////////////////////////////////////

    /**
     * Determine if the ticket is assignable.
     */
    public function isAssignable(): bool
    {
        return $this->assignee_id === null;
    }

    // SCOPES //////////////////////////////////////////////////////////////////////////////////////

    /**
     * Scope a query to only include tickets that are assignable.
     */
    public function scopeAssignable(Builder $query): void
    {
        $query->whereNull('assignee_id');
    }

    /**
     * Scope a query to only include tickets that are already assigned.
     */
    public function scopeAssigned(Builder $query): void
    {
        $query->whereNotNull('assignee_id');
    }

    /**
     * Scope a query to only include models of a given priority.
     */
    public function scopeOfPriority(Builder $query, Priority $priority): void
    {
        $query->where('priority', $priority->value);
    }

    /**
     * Scope a query to order models by priority from high to low.
     */
    public function scopePrioritized(Builder $query): void
    {
        $query->orderBy('priority', 'desc');
    }

    /**
     * Scope a query to only include tickets of a given status.
     */
    public function scopeOfStatus(Builder $query, TicketStatus $status): void
    {
        $query->where('status', $status->value);
    }
}
