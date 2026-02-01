<?php

namespace App\Enums;

use App\Enums\Attributes\Active;
use App\Enums\Concerns\Collectible;
use App\Enums\Concerns\HasActiveCase;
use App\Enums\Concerns\HasCancelledCase;
use App\Enums\Concerns\HasNextCase;

enum OrderStatus: string
{
    use Collectible,
        HasActiveCase,
        HasCancelledCase,
        HasNextCase;

    /**
     * Represents an order that has been created and is awaiting processing.
     *
     * @default
     */
    #[Active]
    case Created = 'created';

    /**
     * Represents an order that has been shipped to the customer.
     */
    #[Active]
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
