<?php

namespace App\Enums\Concerns;

trait HasProgress
{
    /**
     * Get list of pending enum cases.
     *
     * @return list<self>
     */
    abstract public static function pendingCases(): array;

    /**
     * Get list of complete enum cases.
     *
     * @return list<self>
     */
    abstract public static function completeCases(): array;

    /**
     * Determine if the enum case is pending.
     */
    public function isPending(): bool
    {
        return in_array($this, static::pendingCases());
    }

    /**
     * Determine if the enum case is void (excluded from progress calculations).
     */
    public function isVoid(): bool
    {
        return ! $this->isPending() && ! $this->isComplete();
    }

    /**
     * Determine if the enum case is complete.
     */
    public function isComplete(): bool
    {
        return in_array($this, static::completeCases());
    }
}
