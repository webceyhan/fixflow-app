<?php

namespace App\Enums\Concerns;

use Illuminate\Support\Collection;

trait HasValues
{
    /**
     * Get all enum cases as a collection.
     */
    public static function all(): Collection
    {
        return collect(static::cases());
    }

    /**
     * Get all enum values as an array.
     */
    public static function values(): array
    {
        return array_column(static::cases(), 'value');
    }
}
