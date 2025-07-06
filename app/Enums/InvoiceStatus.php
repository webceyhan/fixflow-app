<?php

namespace App\Enums;

use App\Enums\Concerns\HasProgress;
use App\Enums\Concerns\HasValues;

enum InvoiceStatus: string
{
    use HasProgress, HasValues;

    /**
     * Invoice has been created and is awaiting approval.
     */
    case Draft = 'draft';

    /**
     * Invoice has been issued and is ready for review.
     */
    case Issued = 'issued';

    /**
     * Invoice has been sent to the customer.
     */
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

    // METHODS /////////////////////////////////////////////////////////////////////////////////////

    public static function pendingCases(): array
    {
        return [
            self::Draft,
            self::Issued,
            self::Sent,
        ];
    }

    public static function completeCases(): array
    {
        return [
            self::Paid,
            self::Refunded,
        ];
    }
}
