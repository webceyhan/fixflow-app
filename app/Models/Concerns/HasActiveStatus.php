<?php

namespace App\Models\Concerns;

use Illuminate\Database\Eloquent\Builder;

trait HasActiveStatus
{
    private const STATUS = 'status';

    /**
     * Determine if the model status is active.
     */
    public function isActive(): bool
    {
        return $this->{static::STATUS}->isActive();
    }

    // SCOPES //////////////////////////////////////////////////////////////////////////////////////

    /**
     * Scope a query to only include active models.
     */
    public function scopeActive(Builder $query): void
    {
        $query->whereIn(static::STATUS, $this->{static::STATUS}::activeCases());
    }

    /**
     * Scope a query to only include not active models.
     */
    public function scopeNotActive(Builder $query): void
    {
        $query->whereNot(fn (Builder $q) => $q->active());
    }
}
