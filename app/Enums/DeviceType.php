<?php

namespace App\Enums;

use App\Enums\Attributes\Cellular;
use App\Enums\Concerns\Collectible;
use App\Enums\Concerns\HasAttributes;
use App\Enums\Concerns\HasNextCase;

enum DeviceType: string
{
    use Collectible,
        HasAttributes,
        HasNextCase;

    /**
     * Represents a mobile phone device.
     */
    #[Cellular]
    case Phone = 'phone';

    /**
     * Represents a tablet device.
     */
    #[Cellular]
    case Tablet = 'tablet';

    /**
     * Represents a laptop device.
     */
    case Laptop = 'laptop';

    /**
     * Represents a desktop computer device.
     */
    case Desktop = 'desktop';

    /**
     * Represents a device that is worn on the body.
     */
    #[Cellular]
    case Wearable = 'wearable';

    /**
     * Represents any other device not listed such as smart TV,
     * navigation system, game console, peripheral devices, etc.
     *
     * @default
     */
    case Other = 'other';

    // METHODS /////////////////////////////////////////////////////////////////////////////////////

    /**
     * Check if the device type has cellular connectivity.
     *
     * Devices with cellular support can have IMEI numbers and connect to mobile networks.
     */
    public function hasCellular(): bool
    {
        return $this->hasAttribute(Cellular::class);
    }
}
