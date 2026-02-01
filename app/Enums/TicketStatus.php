<?php

namespace App\Enums;

use App\Enums\Attributes\Active;
use App\Enums\Concerns\Collectible;
use App\Enums\Concerns\HasActiveCase;
use App\Enums\Concerns\HasCancelledCase;
use App\Enums\Concerns\HasNextCase;

enum TicketStatus: string
{
    use Collectible,
        HasActiveCase,
        HasCancelledCase,
        HasNextCase;

    /**
     * The ticket has been created and has not been assigned to anyone.
     *
     * @default
     */
    #[Active]
    case Created = 'created';

    /**
     * The ticket has been assigned to a technician and is in progress.
     */
    #[Active]
    case InProgress = 'in_progress';

    /**
     * The ticket is on hold and is not being worked on.
     */
    #[Active]
    case OnHold = 'on_hold';

    /**
     * The ticket has been resolved and is awaiting verification.
     */
    case Resolved = 'resolved';

    /**
     * The ticket has been closed and is no longer active.
     */
    case Closed = 'closed';

    /**
     * The ticket has been cancelled.
     */
    case Cancelled = 'cancelled';
}
