<?php

namespace App\Models\Concerns;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;

/**
 * @property Carbon $due_date
 *
 * @method static Builder|static overdue()
 */
trait HasDueDate
{
    private const DUE_DATE = 'due_date';

    /**
     * Initialize the trait for an instance.
     */
    public function initializeHasDueDate(): void
    {
        $this->casts[static::DUE_DATE] = 'date';
    }

    /**
     * Determine if the model is overdue.
     */
    public function isOverdue(): bool
    {
        return $this->{static::DUE_DATE}->isPast();
    }

    // SCOPES //////////////////////////////////////////////////////////////////////////////////////

    /**
     * Scope a query to only include overdue models.
     */
    public function scopeOverdue(Builder $query): void
    {
        $query->where(static::DUE_DATE, '<', now());
    }
}
