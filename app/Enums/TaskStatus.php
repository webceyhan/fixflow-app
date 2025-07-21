<?php

namespace App\Enums;

use App\Enums\Attributes\Complete;
use App\Enums\Attributes\Pending;
use App\Enums\Concerns\HasNext;
use App\Enums\Concerns\HasProgress;
use App\Enums\Concerns\HasValues;

enum TaskStatus: string
{
    use HasNext, HasProgress, HasValues;

    /**
     * Represents an task that has been created and is pending approval.
     *
     * @default
     */
    #[Pending]
    case New = 'new';

    /**
     * Represents an task that has been completed.
     */
    #[Complete]
    case Completed = 'completed';

    /**
     * Represents an task that has been cancelled.
     */
    case Cancelled = 'cancelled';

}
