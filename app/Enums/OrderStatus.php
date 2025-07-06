<?php

namespace App\Enums;

use App\Enums\Concerns\HasProgress;
use App\Enums\Concerns\HasValues;

enum OrderStatus: string
{
    use HasProgress, HasValues;

    /**
     * Represents an order that has been created and is awaiting processing.
     *
     * @default
     */
    case New = 'new';

    /**
     * Represents an order that has been shipped to the customer.
     */
    case Shipped = 'shipped';

    /**
     * Represents an order that has been received by the customer.
     */
    case Received = 'received';

    /**
     * Represents an order that has been cancelled by the customer or by the system.
     */
    case Cancelled = 'cancelled';

    // METHODS /////////////////////////////////////////////////////////////////////////////////////

    public static function pendingCases(): array
    {
        return [
            self::New,
            self::Shipped,
        ];
    }

    public static function completeCases(): array
    {
        return [
            self::Received,
        ];
    }
}
