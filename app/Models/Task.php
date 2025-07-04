<?php

namespace App\Models;

use App\Enums\TaskStatus;
use App\Enums\TaskType;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    /** @use HasFactory<\Database\Factories\TaskFactory> */
    use HasFactory;

    /**
     * The model's default values for attributes.
     *
     * @var array
     */
    protected $attributes = [
        'is_billable' => true,
        'type' => TaskType::Repair,
        'status' => TaskStatus::New,
    ];

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
        'status',
        'approved_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'cost' => 'float',
        'is_billable' => 'boolean',
        'type' => TaskType::class,
        'status' => TaskStatus::class,
        'approved_at' => 'datetime',
    ];

    // RELATIONS ///////////////////////////////////////////////////////////////////////////////////

    /**
     * Get the ticket that the task belongs to.
     */
    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }

    // SCOPES //////////////////////////////////////////////////////////////////////////////////////

    /**
     * Scope a query to only include tasks that are billable.
     */
    public function scopeBillable(Builder $query): void
    {
        $query->where('is_billable', true);
    }

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

    /**
     * Scope a query to only include approved tasks.
     */
    public function scopeApproved(Builder $query): void
    {
        $query->whereNotNull('approved_at');
    }
}
