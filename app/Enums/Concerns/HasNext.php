<?php

namespace App\Enums\Concerns;

trait HasNext
{
    /**
     * Get the next enum case in sequence, rewinding to first case if at the end.
     */
    public function next(): static
    {
        $cases = static::cases();

        // Find the current case index
        $currentIndex = array_search($this, $cases, true);

        // Return next case or rewind to first
        return $cases[$currentIndex + 1] ?? $cases[0];
    }
}
