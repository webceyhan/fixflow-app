<?php

namespace App\Casts;

use Illuminate\Database\Eloquent\Casts\Attribute;

class Progress extends Attribute
{
    /**
     * Create a getter attribute to calculate progress percentage
     * using the specified model attribute keys for pending and complete counts.
     */
    public static function using(string $pendingCountKey, string $completeCountKey): static
    {
        return parent::get(fn (mixed $value, array $attributes) => static::calculate(
            $attributes[$pendingCountKey] ?? 0,
            $attributes[$completeCountKey] ?? 0,
        ));
    }

    /**
     * Calculate the progress percentage based on pending and complete counts.
     */
    private static function calculate(int $pendingCount, int $completeCount): float
    {
        $totalCount = $pendingCount + $completeCount;

        // Avoid division by zero
        if ($totalCount === 0) {
            return 0.0;
        }

        return round(($completeCount / $totalCount) * 100, 2);
    }
}
