<?php

namespace App\Enums\Concerns;

use App\Enums\Attributes\Complete;
use App\Enums\Attributes\Pending;

trait HasProgress
{
    use HasAttributes;

    /**
     * Get list of pending enum cases.
     *
     * @return list<self>
     */
    public static function pendingCases(): array
    {
        return static::casesByAttribute(Pending::class);
    }

    /**
     * Get list of complete enum cases.
     *
     * @return list<self>
     */
    public static function completeCases(): array
    {
        return static::casesByAttribute(Complete::class);
    }

    /**
     * Determine if the enum case is pending.
     */
    public function isPending(): bool
    {
        return $this->hasAttribute(Pending::class);
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
        return $this->hasAttribute(Complete::class);
    }
}
