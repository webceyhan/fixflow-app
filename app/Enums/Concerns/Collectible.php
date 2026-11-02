<?php

namespace App\Enums\Concerns;

use Illuminate\Support\Collection;

/**
 * @method static array<int, static> cases()
 */
trait Collectible
{
    /**
     * Get all enum cases as a collection.
     *
     * @return Collection<int, static>
     */
    public static function all(): Collection
    {
        return collect(static::cases());
    }
}
