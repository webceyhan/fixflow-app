<?php

namespace App\Enums;

use App\Enums\Attributes\Complete;
use App\Enums\Attributes\Pending;
use App\Enums\Concerns\HasNext;
use App\Enums\Concerns\HasProgress;
use App\Enums\Concerns\HasValues;

enum TicketStatus: string
{
    use HasNext, HasProgress, HasValues;

    /**
     * The ticket is new and has not been assigned to anyone.
     *
     * @default
     */
    #[Pending]
    case New = 'new';

    /**
     * The ticket has been assigned to a technician and is in progress.
     */
    #[Pending]
    case InProgress = 'in_progress';

    /**
     * The ticket is on hold and is not being worked on.
     */
    #[Pending]
    case OnHold = 'on_hold';

    /**
     * The ticket has been resolved and is awaiting verification.
     */
    #[Complete]
    case Resolved = 'resolved';

    /**
     * The ticket has been closed and is no longer active.
     */
    #[Complete]
    case Closed = 'closed';

}
