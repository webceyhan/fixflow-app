<?php

namespace App\Enums;

use App\Enums\Attributes\Active;
use App\Enums\Concerns\Collectible;
use App\Enums\Concerns\HasActiveCase;
use App\Enums\Concerns\HasCancelledCase;
use App\Enums\Concerns\HasNextCase;

enum InvoiceStatus: string
{
    use Collectible,
        HasActiveCase,
        HasCancelledCase,
        HasNextCase;

    /**
     * Invoice has been created and is awaiting approval.
     */
    #[Active]
    case Draft = 'draft';

    /**
     * Invoice has been issued and is ready for review.
     */
    #[Active]
    case Issued = 'issued';

    /**
     * Invoice has been sent to the customer.
     */
    #[Active]
    case Sent = 'sent';

    /**
     * Invoice has been paid by the customer.
     */
    case Paid = 'paid';

    /**
     * Invoice has been refunded to the customer.
     */
    case Refunded = 'refunded';

    /**
     * Invoice has been cancelled.
     */
    case Cancelled = 'cancelled';
}
