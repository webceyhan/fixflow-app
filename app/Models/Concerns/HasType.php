<?php

namespace App\Models\Concerns;

use BackedEnum;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * @template TModel of Model
 * @template TType of BackedEnum
 */
trait HasType
{
    private const TYPE = 'type';

    /**
     * Initialize the trait for an instance.
     */
    public function initializeHasType(): void
    {
        // Get the default type value from the model's attributes
        $defaultType = $this->attributes[self::TYPE] ?? null;

        // Use the class of the default type
        $this->casts[self::TYPE] = $defaultType::class;
    }

    // SCOPES //////////////////////////////////////////////////////////////////////////////////////

    /**
     * Scope a query to only include records with the specified type.
     *
     * @param  Builder<TModel>  $query
     * @param  TType  $type
     */
    public function scopeOfType(Builder $query, BackedEnum $type): void
    {
        $query->where(self::TYPE, $type->value);
    }
}
