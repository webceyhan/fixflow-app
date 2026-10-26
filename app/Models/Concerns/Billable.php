<?php

namespace App\Models\Concerns;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * @template TModel of Model
 *
 * @mixin TModel
 */
trait Billable
{
    private const IS_BILLABLE = 'is_billable';

    /**
     * Initialize the trait for an instance.
     */
    public function initializeBillable(): void
    {
        $this->attributes[self::IS_BILLABLE] = true;

        $this->casts[self::IS_BILLABLE] = 'boolean';
    }

    /**
     * Determine if the model is billable.
     */
    public function isBillable(): bool
    {
        return (bool) $this->{self::IS_BILLABLE};
    }

    // SCOPES //////////////////////////////////////////////////////////////////////////////////////

    /**
     * Scope a query to only include billable models.
     *
     * @param  Builder<TModel>  $query
     */
    public function scopeBillable(Builder $query): void
    {
        $query->where(self::IS_BILLABLE, true);
    }

    /**
     * Scope a query to only include models that are not billable.
     *
     * @param  Builder<TModel>  $query
     */
    public function scopeNotBillable(Builder $query): void
    {
        $query->where(self::IS_BILLABLE, false);
    }
}
