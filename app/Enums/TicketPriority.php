<?php

namespace App\Enums;

use App\Enums\Concerns\HasValues;

enum TicketPriority: string
{
    use HasValues;

    /**
     * The ticket has a low priority and can be addressed later.
     */
    case Low = 'low';

    /**
     * The ticket has a normal priority and should be addressed soon.
     *
     * @default
     */
    case Normal = 'normal';

    /**
     * The ticket has a high priority and should be addressed as soon as possible.
     */
    case High = 'high';
}
