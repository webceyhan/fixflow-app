<?php

namespace App\Enums;

use App\Enums\Concerns\Completable;
use App\Enums\Concerns\HasValues;

enum TicketStatus: string
{
    use HasValues, Completable;

    /**
     * The ticket is new and has not been assigned to anyone.
     * @default
     */
    case New = 'new';

    /**
     * The ticket has been assigned to a technician and is in progress.
     */
    case InProgress = 'in_progress';

    /**
     * The ticket is on hold and is not being worked on.
     */
    case OnHold = 'on_hold';

    /**
     * The ticket has been resolved and is awaiting verification.
     */
    case Resolved = 'resolved';

    /**
     * The ticket has been closed and is no longer active.
     */
    case Closed = 'closed';

    // METHODS /////////////////////////////////////////////////////////////////////////////////////

    /**
     * Get list of completed enum cases.
     */
    public static function completedCases(): array
    {
        return [
            self::Resolved,
            self::Closed,
        ];
    }
}
