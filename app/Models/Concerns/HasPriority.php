<?php

namespace App\Models\Concerns;

use App\Enums\Priority;
use Illuminate\Database\Eloquent\Builder;

/**
 * @property Priority $priority
 * 
 * @method static Builder|static ofPriority(Priority $priority)
 * @method static Builder|static prioritized()
 */
trait HasPriority
{
    private const PRIORITY = 'priority';

    /**
     * Initialize the trait for an instance.
     */
    public function initializeHasPriority(): void
    {
        $this->casts[static::PRIORITY] = Priority::class;

        $this->attributes[static::PRIORITY] = Priority::Normal;
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
     * Scope a query to order models by priority from high to low.
     */
    public function scopePrioritized(Builder $query): void
    {
        $query->orderBy(static::PRIORITY, 'desc');
    }
}
