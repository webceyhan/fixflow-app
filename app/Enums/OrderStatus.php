<?php

namespace App\Enums;

use App\Enums\Concerns\HasValues;

enum OrderStatus: string
{
    use HasValues;

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
}
