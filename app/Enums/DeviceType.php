<?php

namespace App\Enums;

use App\Enums\Concerns\Collectible;
use App\Enums\Concerns\HasNextCase;

enum DeviceType: string
{
    use Collectible, HasNextCase;

    /**
     * Represents a mobile phone device.
     */
    case Phone = 'phone';

    /**
     * Represents a tablet device.
     */
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
    case Wearable = 'wearable';

    /**
     * Represents any other device not listed such as smart TV,
     * navigation system, game console, peripheral devices, etc.
     *
     * @default
     */
    case Other = 'other';
}
