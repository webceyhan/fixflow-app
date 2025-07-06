<?php

namespace App\Enums;

use App\Enums\Concerns\HasProgress;
use App\Enums\Concerns\HasValues;

enum TaskStatus: string
{
    use HasProgress, HasValues;

    /**
     * Represents an task that has been created and is pending approval.
     *
     * @default
     */
    case New = 'new';

    /**
     * Represents an task that has been completed.
     */
    case Completed = 'completed';

    /**
     * Represents an task that has been cancelled.
     */
    case Cancelled = 'cancelled';

    // METHODS /////////////////////////////////////////////////////////////////////////////////////

    public static function pendingCases(): array
    {
        return [
            self::New,
        ];
    }

    public static function completeCases(): array
    {
        return [
            self::Completed,
        ];
    }
}
