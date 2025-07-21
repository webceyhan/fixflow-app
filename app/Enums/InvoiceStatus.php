<?php

namespace App\Enums;

use App\Enums\Attributes\Complete;
use App\Enums\Attributes\Pending;
use App\Enums\Concerns\HasNext;
use App\Enums\Concerns\HasProgress;
use App\Enums\Concerns\HasValues;

enum InvoiceStatus: string
{
    use HasNext, HasProgress, HasValues;

    /**
     * Invoice has been created and is awaiting approval.
     */
    #[Pending]
    case Draft = 'draft';

    /**
     * Invoice has been issued and is ready for review.
     */
    #[Pending]
    case Issued = 'issued';

    /**
     * Invoice has been sent to the customer.
     */
    #[Pending]
    case Sent = 'sent';

    /**
     * Invoice has been paid by the customer.
     */
    #[Complete]
    case Paid = 'paid';

    /**
     * Invoice has been refunded to the customer.
     */
    #[Complete]
    case Refunded = 'refunded';

    /**
     * Invoice has been cancelled.
     */
    case Cancelled = 'cancelled';

}
