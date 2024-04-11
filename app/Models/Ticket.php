<?php

namespace App\Models;

use App\Enums\Priority;
use App\Enums\TicketStatus;
use Database\Factories\TicketFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property string $description
 * @property Priority $priority
 * @property TicketStatus $status
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * 
 * @method static TicketFactory factory(int $count = null, array $state = [])
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

    // SCOPES //////////////////////////////////////////////////////////////////////////////////////

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
