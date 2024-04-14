<?php

namespace App\Models\Concerns;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;

/**
 * @property Carbon|null $warranty_expire_date
 * 
 * @method static Builder|static withWarranty()
 */
trait HasWarranty
{
    private const WARRANTY_EXPIRE_DATE = 'warranty_expire_date';

    /**
     * Initialize the trait for an instance.
     */
    public function initializeHasWarranty(): void
    {
        $this->casts[static::WARRANTY_EXPIRE_DATE] = 'date';
    }

    /**
     * Determine if the model has valid warranty.
     */
    public function hasWarranty(): bool
    {
        return $this->warranty_expire_date > now();
    }

    // SCOPES //////////////////////////////////////////////////////////////////////////////////////

    /**
     * Scope a query to only include models with warranty.
     */
    public function scopeWithWarranty(Builder $query): void
    {
        $query->where(self::WARRANTY_EXPIRE_DATE, '>', now());
    }
}
