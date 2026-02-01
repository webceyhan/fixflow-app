<?php

namespace App\Enums\Concerns;

/**
 * @phpstan-require-extends \UnitEnum
 *
 * @method static self Cancelled
 */
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
