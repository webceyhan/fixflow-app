<?php

namespace App\Enums;

use App\Enums\Concerns\HasValues;

enum TaskStatus: string
{
    use HasValues;

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
}
