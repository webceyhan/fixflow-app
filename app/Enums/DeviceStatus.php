<?php

namespace App\Enums;

use App\Enums\Concerns\Collectible;
use App\Enums\Concerns\HasActiveCase;
use App\Enums\Concerns\HasNextCase;

enum DeviceStatus: string
{
    use Collectible, HasActiveCase, HasNextCase;

    /**
     * Device has been received and logged into the store.
     *
     * @default
     */
    case Received = 'received';

    /**
     * Device is currently being serviced (repairs, upgrades, installations, etc.).
     * Detailed service/repair states are tracked in the associated ticket/RMA.
     */
    case InService = 'in_service';

    /**
     * Service is complete and device is ready for customer pickup.
     */
    case Ready = 'ready';

    /**
     * Device has been delivered to customer and left the store.
     */
    case Delivered = 'delivered';

    // METHODS /////////////////////////////////////////////////////////////////////////////////////

    /**
     * Get the list of pending device statuses.
     */
    public static function activeCases(): array
    {
        return [
            self::Received,
            self::InService,
        ];
    }
}
