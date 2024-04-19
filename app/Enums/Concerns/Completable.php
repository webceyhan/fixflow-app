<?php

namespace App\Enums\Concerns;

trait Completable
{
    /**
     * Get list of completed enum cases.
     *
     * @return array<int, static>
     */
    abstract public static function completedCases(): array;

    /**
     * Determine if the enum case is completed.
     */
    public function isCompleted(): bool
    {
        return in_array($this, static::completedCases());
    }
}
