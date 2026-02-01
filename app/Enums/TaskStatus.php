<?php

namespace App\Enums;

use App\Enums\Concerns\Collectible;
use App\Enums\Concerns\HasActiveCase;
use App\Enums\Concerns\HasCancelledCase;
use App\Enums\Concerns\HasNextCase;

enum TaskStatus: string
{
    use Collectible, HasActiveCase, HasCancelledCase, HasNextCase;

    /**
     * The task has been created and is new.
     *
     * @default
     */
    case Created = 'created';

    /**
     * The task has been completed successfully.
     */
    case Completed = 'completed';

    /**
     * The task has been cancelled and will not be completed.
     */
    case Cancelled = 'cancelled';

    // METHODS /////////////////////////////////////////////////////////////////////////////////////

    /**
     * Get the list of active task statuses.
     */
    public static function activeCases(): array
    {
        return [
            self::Created,
        ];
    }
}
