<?php

namespace App\Enums\Concerns;

trait HasCancelledCase
{
    /**
     * Determine if the enum case is cancelled.
     */
    public function isCancelled(): bool
    {
        return $this->value === 'cancelled';
    }
}
