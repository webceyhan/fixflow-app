<?php

namespace App\Enums;

use App\Enums\Concerns\HasProgress;
use App\Enums\Concerns\HasValues;

enum DeviceStatus: string
{
    use HasProgress, HasValues;

    /**
     * Device has been received and is awaiting repair.
     */
    case Received = 'received';

    /**
     * Device is currently on hold due to pending approval or parts.
     */
    case OnHold = 'on_hold';

    /**
     * Device is currently under repair by a technician.
     */
    case UnderRepair = 'under_repair';

    /**
     * Repair work has been completed and device is ready for pickup.
     */
    case Ready = 'ready';

    /**
     * Device has been delivered to the customer.
     */
    case Delivered = 'delivered';

    // METHODS /////////////////////////////////////////////////////////////////////////////////////

    public static function pendingCases(): array
    {
        return [
            self::Received,
            self::OnHold,
            self::UnderRepair,
        ];
    }

    public static function completeCases(): array
    {
        return [
            self::Ready,
            self::Delivered,
        ];
    }
}
