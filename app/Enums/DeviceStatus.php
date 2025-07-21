<?php

namespace App\Enums;

use App\Enums\Attributes\Complete;
use App\Enums\Attributes\Pending;
use App\Enums\Concerns\HasNext;
use App\Enums\Concerns\HasProgress;
use App\Enums\Concerns\HasValues;

enum DeviceStatus: string
{
    use HasNext, HasProgress, HasValues;

    /**
     * Device has been received and is awaiting repair.
     */
    #[Pending]
    case Received = 'received';

    /**
     * Device is currently on hold due to pending approval or parts.
     */
    #[Pending]
    case OnHold = 'on_hold';

    /**
     * Device is currently under repair by a technician.
     */
    #[Pending]
    case UnderRepair = 'under_repair';

    /**
     * Repair work has been completed and device is ready for pickup.
     */
    #[Complete]
    case Ready = 'ready';

    /**
     * Device has been delivered to the customer.
     */
    #[Complete]
    case Delivered = 'delivered';
}
