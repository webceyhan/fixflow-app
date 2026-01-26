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
     * Determine if the model is active.
     */
    abstract public function isActive(): bool;

    /**
     * Determine if the model is overdue.
     */
    public function isOverdue(): bool
    {
        return $this->isActive() && $this->{static::DUE_DATE}?->isPast();
    }

    // SCOPES //////////////////////////////////////////////////////////////////////////////////////

    /**
     * Scope a query to only include active models.
     */
    abstract public function scopeActive(Builder $query): void;

    /**
     * Scope a query to only include overdue models.
     */
    public function scopeOverdue(Builder $query): void
    {
        $query
            ->active()
            ->whereNotNull(static::DUE_DATE)
            ->where(static::DUE_DATE, '<', now()->startOfDay());
    }

    /**
     * Scope a query to only include models that are not overdue.
     */
    public function scopeNotOverdue(Builder $query): void
    {
        $query
            ->whereNull(static::DUE_DATE)
            ->orWhere(static::DUE_DATE, '>=', now()->startOfDay());
    }
}
