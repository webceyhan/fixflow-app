<?php

namespace App\Models\Concerns;

use Illuminate\Database\Eloquent\Builder;

trait HasCancelledStatus
{
    private const STATUS = 'status';

    private const VALUE = 'cancelled';

    /**
     * Determine if the model status is cancelled.
     */
    public function isCancelled(): bool
    {
        return $this->{self::STATUS}->value === self::VALUE;
    }

    // SCOPES //////////////////////////////////////////////////////////////////////////////////////

    /**
     * Scope a query to only include cancelled models.
     */
    public function scopeCancelled(Builder $query): void
    {
        $query->where(self::STATUS, self::VALUE);
    }

    /**
     * Scope a query to only include not cancelled models.
     */
    public function scopeNotCancelled(Builder $query): void
    {
        $query->where(self::STATUS, '!=', self::VALUE);
    }
}
