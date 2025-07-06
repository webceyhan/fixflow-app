<?php

namespace App\Models\Concerns;

use Illuminate\Database\Eloquent\Builder;

trait HasProgress
{
    private const STATUS = 'status';

    /**
     * Determine if the model status is pending.
     */
    public function isPending(): bool
    {
        return $this->{static::STATUS}->isPending();
    }

    /**
     * Determine if the model status is void (excluded from progress calculations).
     */
    public function isVoid(): bool
    {
        return $this->{static::STATUS}->isVoid();
    }

    /**
     * Determine if the model status is complete.
     */
    public function isComplete(): bool
    {
        return $this->{static::STATUS}->isComplete();
    }

    // SCOPES //////////////////////////////////////////////////////////////////////////////////////

    /**
     * Scope a query to only include pending models.
     */
    public function scopePending(Builder $query): void
    {
        $query->whereIn(static::STATUS, $this->{static::STATUS}::pendingCases());
    }

    /**
     * Scope a query to only include void models.
     */
    public function scopeVoid(Builder $query): void
    {
        $query->whereNotIn(static::STATUS, [
            ...$this->{static::STATUS}::pendingCases(),
            ...$this->{static::STATUS}::completeCases(),
        ]);
    }

    /**
     * Scope a query to only include complete models.
     */
    public function scopeComplete(Builder $query): void
    {
        $query->whereIn(static::STATUS, $this->{static::STATUS}::completeCases());
    }
}
