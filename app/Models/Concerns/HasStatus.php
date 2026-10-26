<?php

namespace App\Models\Concerns;

use BackedEnum;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * @template TModel of Model
 * @template TStatus of BackedEnum
 *
 * @mixin TModel
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
        $defaultStatus = $this->attributes[self::STATUS] ?? null;

        // Use the class of the default status
        $this->casts[self::STATUS] = $defaultStatus::class;
    }

    // SCOPES //////////////////////////////////////////////////////////////////////////////////////

    /**
     * Scope a query to only include users with the specified status.
     *
     * @param  Builder<TModel>  $query
     * @param  TStatus  $status
     */
    public function scopeOfStatus(Builder $query, BackedEnum $status): void
    {
        $query->where(self::STATUS, $status->value);
    }
}
