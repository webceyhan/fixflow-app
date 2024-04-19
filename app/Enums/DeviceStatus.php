<?php

namespace App\Enums;

use App\Enums\Concerns\HasValues;

enum DeviceStatus: string
{
    use HasValues;

    /**
     * Represents a device that has been checked in and is awaiting repair.
     * @default
     */
    case CheckedIn = 'checked_in';

    /**
     * Represents a device that is currently on hold due to pending approval or parts.
     */
    case OnHold = 'on_hold';

    /**
     * Represents a device that is currently being repaired by a technician.
     */
    case InRepair = 'in_repair';

    /**
     * Represents that the repair work has been completed, ready to picked up.
     */
    case Finished = 'finished';

    /**
     * Represents a device that has been returned to the customer.
     */
    case CheckedOut = 'checked_out';
}
