<?php

namespace App\Enums;

use App\Enums\Concerns\HasNext;
use App\Enums\Concerns\HasValues;

enum DeviceType: string
{
    use HasNext, HasValues;

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
