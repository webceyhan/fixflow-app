<?php

namespace App\Models;

use App\Enums\TaskStatus;
use App\Enums\TaskType;
use App\Models\Concerns\Billable;
use App\Models\Concerns\Completable;
use Database\Factories\TaskFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int $ticket_id
 * @property string $description
 * @property float $cost
 * @property bool $is_billable
 * @property TaskType $type
 * @property TaskStatus $status
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * 
 * @property-read Ticket $ticket
 * 
 * @method static TaskFactory factory(int $count = null, array $state = [])
 * @method static Builder|static ofType(TaskType $type)
 * @method static Builder|static ofStatus(TaskStatus $status)
 */
class Task extends Model
{
    use HasFactory, Billable, Completable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'description',
        'cost',
        'is_billable',
        'type',
        'status'
    ];

    /**
     * The model's default values for attributes.
     *
     * @var array
     */
    protected $attributes = [
        'type' => TaskType::Repair,
        'status' => TaskStatus::New,
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'type' => TaskType::class,
            'status' => TaskStatus::class,
        ];
    }

    // RELATIONS ///////////////////////////////////////////////////////////////////////////////////

    public function ticket(): BelongsTo
    {
        return $this->belongsTo(Ticket::class);
    }

    // SCOPES //////////////////////////////////////////////////////////////////////////////////////

    /**
     * Scope a query to only include tasks of a given type.
     */
    public function scopeOfType(Builder $query, TaskType $type): void
    {
        $query->where('type', $type->value);
    }

    /**
     * Scope a query to only include tasks of a given status.
     */
    public function scopeOfStatus(Builder $query, TaskStatus $status): void
    {
        $query->where('status', $status->value);
    }
}
