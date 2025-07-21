<?php

namespace App\Enums;

use App\Enums\Attributes\Complete;
use App\Enums\Attributes\Pending;
use App\Enums\Concerns\HasNext;
use App\Enums\Concerns\HasProgress;
use App\Enums\Concerns\HasValues;

enum OrderStatus: string
{
    use HasNext, HasProgress, HasValues;

    /**
     * Represents an order that has been created and is awaiting processing.
     *
     * @default
     */
    #[Pending]
    case New = 'new';

    /**
     * Represents an order that has been shipped to the customer.
     */
    #[Pending]
    case Shipped = 'shipped';

    /**
     * Represents an order that has been received by the customer.
     */
    #[Complete]
    case Received = 'received';

    /**
     * Represents an order that has been cancelled by the customer or by the system.
     */
    case Cancelled = 'cancelled';

}
