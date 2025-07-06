<?php

namespace App\Models\Concerns;

use Illuminate\Database\Eloquent\Builder;

trait Billable
{
    private const IS_BILLABLE = 'is_billable';

    /**
     * Initialize the trait for an instance.
     */
    public function initializeBillable(): void
    {
        $this->casts[static::IS_BILLABLE] = 'boolean';

        $this->attributes[static::IS_BILLABLE] = true;
    }

    /**
     * Determine if the model is billable.
     */
    public function isBillable(): bool
    {
        return (bool) $this->{static::IS_BILLABLE};
    }

    // SCOPES //////////////////////////////////////////////////////////////////////////////////////

    /**
     * Scope a query to only include billable models.
     */
    public function scopeBillable(Builder $query): void
    {
        $query->where(static::IS_BILLABLE, true);
    }

    /**
     * Scope a query to only include models that are not billable.
     */
    public function scopeNotBillable(Builder $query): void
    {
        $query->where(static::IS_BILLABLE, false);
    }
}
