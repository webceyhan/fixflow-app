<?php

namespace App\Models\Concerns;

use BackedEnum;
use Illuminate\Database\Eloquent\Builder;

/**
 * @template TStatus of BackedEnum
 */
trait HasStatus
{
    private const STATUS = 'status';

    /**
     * Initialize the trait for an instance.
     */
    public function initializeHasStatus(): void
    {
        // Get the default status value from the model's attributes
        $defaultStatus = $this->attributes[static::STATUS] ?? null;

        // Use the class of the default status
        $this->casts[static::STATUS] = $defaultStatus::class;
    }

    // SCOPES //////////////////////////////////////////////////////////////////////////////////////

    /**
     * Scope a query to only include users with the specified status.
     *
     * @param  TStatus  $status
     */
    public function scopeOfStatus(Builder $query, BackedEnum $status): void
    {
        $query->where(self::STATUS, $status->value);
    }
}
