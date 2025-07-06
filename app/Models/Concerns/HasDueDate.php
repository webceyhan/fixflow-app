<?php

namespace App\Models\Concerns;

use Illuminate\Database\Eloquent\Builder;

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
        return $this->{static::DUE_DATE}?->startOfDay()->isPast()
            && $this->isPending();
    }

    // SCOPES //////////////////////////////////////////////////////////////////////////////////////

    /**
     * Scope a query to only include overdue models.
     */
    public function scopeOverdue(Builder $query): void
    {
        $query
            ->where(static::DUE_DATE, '<', now()->startOfDay())
            ->pending();
    }

    /**
     * Scope a query to only include models that are not overdue.
     */
    public function scopeNotOverdue(Builder $query): void
    {
        $query
            ->whereNotNull(static::DUE_DATE)
            ->where(static::DUE_DATE, '>=', now()->startOfDay());
    }
}
