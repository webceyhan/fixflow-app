<?php

namespace App\Enums\Concerns;

use Illuminate\Support\Collection;

trait Collectible
{
    /**
     * Get all enum cases as a collection.
     */
    public static function all(): Collection
    {
        return collect(static::cases());
    }
}
