<?php

namespace App\Models\Concerns;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * @template TModel of Model
 *
 * @mixin TModel
 */
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
     *
     * @param  Builder<TModel>  $query
     */
    public function scopeCancelled(Builder $query): void
    {
        $query->where(self::STATUS, self::VALUE);
    }

    /**
     * Scope a query to only include not cancelled models.
     *
     * @param  Builder<TModel>  $query
     */
    public function scopeNotCancelled(Builder $query): void
    {
        $query->where(self::STATUS, '!=', self::VALUE);
    }
}
