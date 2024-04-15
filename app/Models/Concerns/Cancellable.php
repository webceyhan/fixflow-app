<?php

namespace App\Models\Concerns;

use Illuminate\Database\Eloquent\Builder;

/**
 * @method static Builder|static cancelled()
 */
trait Cancellable
{
    private const STATUS = 'status';
    private const CANCELLED = 'cancelled';

    /**
     * Determine if the model status is cancelled.
     */
    public function isCancelled(): bool
    {
        return $this->{static::STATUS}->value === static::CANCELLED;
    }

    /**
     * Mark the model as cancelled.
     */
    public function cancel(): void
    {
        $this->forceFill([static::STATUS => static::CANCELLED])->save();
    }

    // SCOPES //////////////////////////////////////////////////////////////////////////////////////

    /**
     * Scope a query to only include models that are cancelled.
     */
    public function scopeCancelled(Builder $query): void
    {
        $query->where(static::STATUS, static::CANCELLED);
    }
}
