<?php

namespace App\Models\Concerns;

use BackedEnum;
use Illuminate\Database\Eloquent\Builder;

/**
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
        $defaultType = $this->attributes[static::TYPE];

        // Use the class of the default type
        $this->casts[static::TYPE] = $defaultType::class;
    }

    // SCOPES //////////////////////////////////////////////////////////////////////////////////////

    /**
     * Scope a query to only include records with the specified type.
     *
     * @param  TType  $type
     */
    public function scopeOfType(Builder $query, BackedEnum $type): void
    {
        $query->where(self::TYPE, $type->value);
    }
}
