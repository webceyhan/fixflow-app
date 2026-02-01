<?php

namespace App\Models;

use App\Enums\Priority;
use App\Enums\TaskStatus;
use App\Enums\TaskType;
use App\Models\Concerns\Approvable;
use App\Models\Concerns\Assignable;
use App\Models\Concerns\Billable;
use App\Models\Concerns\HasActiveStatus;
use App\Models\Concerns\HasCancelledStatus;
use App\Models\Concerns\HasPriority;
use App\Models\Concerns\HasStatus;
use App\Models\Concerns\HasType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Task extends Model
{
    /** @use HasFactory<\Database\Factories\TaskFactory> */
    use Approvable,
        Assignable,
        Billable,
        HasActiveStatus,
        HasCancelledStatus,
        HasFactory,
        HasPriority,
        HasStatus,
        HasType;

    /**
     * The model's default values for attributes.
     *
     * @var array
     */
    protected $attributes = [
        'cost' => 0,
        'priority' => Priority::Medium,
        'type' => TaskType::Repair,
        'status' => TaskStatus::Created,
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'ticket_id',
        'assignee_id',
        'cost',
        'is_billable',
        'note',
        'priority',
        'type',
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
            'cost' => 'float',
        ];
    }

    // RELATIONSHIPS ///////////////////////////////////////////////////////////////////////////////

    /**
     * Get the ticket that owns the task.
     */
    public function ticket(): BelongsTo
    {
        return $this->belongsTo(Ticket::class);
    }
}
