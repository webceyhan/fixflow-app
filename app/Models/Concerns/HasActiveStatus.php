<?php

namespace App\Models\Concerns;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * @template TModel of Model
 *
 * @mixin TModel
 */
trait HasActiveStatus
{
    private const STATUS = 'status';

    /**
     * Determine if the model status is active.
     */
    public function isActive(): bool
    {
        return $this->{self::STATUS}->isActive();
    }

    // SCOPES //////////////////////////////////////////////////////////////////////////////////////

    /**
     * Scope a query to only include active models.
     *
     * @param  Builder<TModel>  $query
     */
    public function scopeActive(Builder $query): void
    {
        $query->whereIn(self::STATUS, $this->{self::STATUS}::activeCases());
    }

    /**
     * Scope a query to only include not active models.
     *
     * @param  Builder<TModel>  $query
     */
    public function scopeNotActive(Builder $query): void
    {
        $query->whereNot(fn (Builder $q) => $q->active()); // @phpstan-ignore-line
    }
}
