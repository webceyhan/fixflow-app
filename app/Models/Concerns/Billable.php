<?php

namespace App\Models\Concerns;

use Illuminate\Database\Eloquent\Builder;

/**
 * @property float $cost
 * @property bool $is_billable
 * 
 * @method static Builder|static billable()
 * @method static Builder|static free()
 */
trait Billable
{
    private const COST = 'cost';
    private const IS_BILLABLE = 'is_billable';

    /**
     * Initialize the trait for an instance.
     */
    public function initializeBillable(): void
    {
        $this->casts[static::COST] = 'float';
        $this->casts[static::IS_BILLABLE] = 'boolean';

        $this->attributes[static::COST] = 0;
        $this->attributes[static::IS_BILLABLE] = true;
    }

    // SCOPES //////////////////////////////////////////////////////////////////////////////////////

    /**
     * Scope a query to only include models that are billable.
     */
    public function scopeBillable(Builder $query): void
    {
        $query->where(static::IS_BILLABLE, true);
    }

    /**
     * Scope a query to only include models that are free of charge.
     */
    public function scopeFree(Builder $query): void
    {
        $query->where(static::IS_BILLABLE, false);
    }
}
