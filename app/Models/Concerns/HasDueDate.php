<?php

namespace App\Models\Concerns;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * @template TModel of Model
 *
 * @mixin TModel
 */
trait HasDueDate
{
    private const DUE_DATE = 'due_date';

    /**
     * Initialize the trait for an instance.
     */
    public function initializeHasDueDate(): void
    {
        $this->casts[self::DUE_DATE] = 'date';
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
        if (! $this->{self::DUE_DATE}) {
            return false;
        }

        return $this->isActive() && $this->{self::DUE_DATE}->isPast();
    }

    // SCOPES //////////////////////////////////////////////////////////////////////////////////////

    /**
     * Scope a query to only include active models.
     *
     * @param  Builder<TModel>  $query
     */
    abstract public function scopeActive(Builder $query): void;

    /**
     * Scope a query to only include overdue models.
     *
     * @param  Builder<TModel>  $query
     */
    public function scopeOverdue(Builder $query): void
    {
        $query
            ->active() // @phpstan-ignore-line
            ->whereNotNull(self::DUE_DATE)
            ->where(self::DUE_DATE, '<', now()->startOfDay());
    }

    /**
     * Scope a query to only include models that are not overdue.
     *
     * @param  Builder<TModel>  $query
     */
    public function scopeNotOverdue(Builder $query): void
    {
        $query
            ->whereNull(self::DUE_DATE)
            ->orWhere(self::DUE_DATE, '>=', now()->startOfDay());
    }
}
