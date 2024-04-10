<?php

namespace App\Enums\Concerns;

trait HasValues
{
    /**
     * Get all enum values as an array.
     */
    public static function values(): array
    {
        return array_column(static::cases(), 'value');
    }
}
