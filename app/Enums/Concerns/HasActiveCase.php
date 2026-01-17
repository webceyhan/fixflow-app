<?php

namespace App\Enums\Concerns;

trait HasActiveCase
{
    /**
     * Get list of active enum cases.
     *
     * @return list<self>
     */
    abstract public static function activeCases(): array;

    /**
     * Determine if the enum case is active.
     */
    public function isActive(): bool
    {
        return in_array($this, self::activeCases(), true);
    }
}
