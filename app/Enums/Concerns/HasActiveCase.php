<?php

namespace App\Enums\Concerns;

use App\Enums\Attributes\Active;

trait HasActiveCase
{
    use HasAttributes;

    /**
     * Determine if the enum case is active.
     */
    public function isActive(): bool
    {
        return $this->hasAttribute(Active::class);
    }

    /**
     * Get list of active enum cases.
     *
     * @return list<self>
     */
    public static function activeCases(): array
    {
        return static::casesByAttribute(Active::class);
    }
}
