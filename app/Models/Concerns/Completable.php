<?php

namespace App\Models\Concerns;

use Illuminate\Database\Eloquent\Builder;

/**
 * @property enum $status
 * 
 * @method static Builder|static pending()
 * @method static Builder|static completed()
 */
trait Completable
{
    private const STATUS = 'status';

    /**
     * Determine if the model status is completed.
     */
    public function isCompleted(): bool
    {
        return $this->{static::STATUS}->isCompleted();
    }

    // SCOPES //////////////////////////////////////////////////////////////////////////////////////

    /**
     * Scope a query to only include models with pending status.
     */
    public function scopePending(Builder $query): void
    {
        $query->whereNot(fn (Builder $query) => $query->completed());
    }

    /**
     * Scope a query to only include models with completed status.
     */
    public function scopeCompleted(Builder $query): void
    {
        $enumClass = $this->casts[static::STATUS];

        $query->whereIn(static::STATUS, $enumClass::completedCases());
    }
}
