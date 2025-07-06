<?php

namespace App\Models\Concerns;

use App\Enums\Priority;
use Illuminate\Database\Eloquent\Builder;

trait HasPriority
{
    private const PRIORITY = 'priority';

    /**
     * Initialize the trait for an instance.
     */
    public function initializeHasPriority(): void
    {
        $this->casts[static::PRIORITY] = Priority::class;

        $this->attributes[static::PRIORITY] = Priority::Medium;
    }

    /**
     * Determine if the model has urgent priority.
     */
    public function isUrgent(): bool
    {
        return $this->{static::PRIORITY} === Priority::Urgent;
    }

    // SCOPES //////////////////////////////////////////////////////////////////////////////////////

    /**
     * Scope a query to only include models with the given priority.
     */
    public function scopeOfPriority(Builder $query, Priority $priority): void
    {
        $query->where(static::PRIORITY, $priority->value);
    }

    /**
     * Scope a query to only include urgent priority models.
     */
    public function scopeUrgent(Builder $query): void
    {
        $query->ofPriority(Priority::Urgent);
    }

    /**
     * Scope a query to order models by priority from high to low.
     */
    public function scopePrioritized(Builder $query): void
    {
        $query->orderByRaw('CASE '.static::PRIORITY.' 
            WHEN ? THEN 1 
            WHEN ? THEN 2 
            WHEN ? THEN 3 
            WHEN ? THEN 4 
            END DESC', Priority::values());
    }
}
