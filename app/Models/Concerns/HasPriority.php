<?php

namespace App\Models\Concerns;

use BackedEnum;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * @template TModel of Model
 * @template TPriority of BackedEnum
 *
 * @mixin TModel
 */
trait HasPriority
{
    private const PRIORITY = 'priority';

    /**
     * Initialize the trait for an instance.
     */
    public function initializeHasPriority(): void
    {
        // Get the default priority value from the model's attributes
        $defaultPriority = $this->attributes[self::PRIORITY] ?? null;

        // Use the class of the default priority
        $this->casts[self::PRIORITY] = $defaultPriority::class;
    }

    // SCOPES //////////////////////////////////////////////////////////////////////////////////////

    /**
     * Scope a query to only include records with the specified priority.
     *
     * @param  Builder<TModel>  $query
     * @param  TPriority  $priority
     */
    public function scopeOfPriority(Builder $query, BackedEnum $priority): void
    {
        $query->where(self::PRIORITY, $priority->value);
    }
}
