<?php

namespace App\Enums;

use App\Enums\Concerns\HasProgress;
use App\Enums\Concerns\HasValues;

enum TicketStatus: string
{
    use HasProgress, HasValues;

    /**
     * The ticket is new and has not been assigned to anyone.
     *
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

    public static function pendingCases(): array
    {
        return [
            self::New,
            self::InProgress,
            self::OnHold,
        ];
    }

    public static function completeCases(): array
    {
        return [
            self::Resolved,
            self::Closed,
        ];
    }
}
