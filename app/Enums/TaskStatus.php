<?php

namespace App\Enums;

use App\Enums\Concerns\Completable;
use App\Enums\Concerns\HasValues;

enum TaskStatus: string
{
    use HasValues, Completable;

    /**
     * Represents an task that has been created and is pending approval.
     * @default
     */
    case New = 'new';

    /**
     * Represents an task that has been approved and ready to be processed.
     */
    case Approved = 'approved';

    /**
     * Represents an task that has been completed.
     */
    case Completed = 'completed';

    /**
     * Represents an task that has been cancelled.
     */
    case Cancelled = 'cancelled';

    // METHODS /////////////////////////////////////////////////////////////////////////////////////

    /**
     * Get list of completed enum cases.
     */
    public static function completedCases(): array
    {
        return [
            self::Completed,
            self::Cancelled,
        ];
    }
}
