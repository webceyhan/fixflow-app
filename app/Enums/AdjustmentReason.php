<?php

namespace App\Enums;

use App\Enums\Concerns\Collectible;
use App\Enums\Concerns\HasNextCase;

enum AdjustmentReason: string
{
    use Collectible, HasNextCase;

    // DISCOUNT REASONS ////////////////////////////////////////////////////////////////////////////

    /**
     * Bulk service discount for multiple services.
     */
    case BulkService = 'bulk_service';

    /**
     * Promotional discount for marketing campaigns.
     */
    case Promotion = 'promotion';

    /**
     * Loyalty discount for repeat customers.
     */
    case Loyalty = 'loyalty';

    /**
     * Welcome bonus for new customers.
     *
     * @default
     */
    case Welcome = 'welcome';

    /**
     * Compensation for service issues or delays.
     */
    case Compensation = 'compensation';

    // FEE REASONS /////////////////////////////////////////////////////////////////////////////////

    /**
     * Rush service fee for expedited completion.
     */
    case RushService = 'rush_service';

    /**
     * Delivery fee for parts or device transportation.
     */
    case Delivery = 'delivery';

    /**
     * Late payment fee for overdue invoices.
     */
    case LatePayment = 'late_payment';

    /**
     * Cancellation fee for cancelled services.
     */
    case Cancellation = 'cancellation';

    // METHODS /////////////////////////////////////////////////////////////////////////////////////

    /**
     * Get the associated adjustment type for this reason.
     */
    public function type(): AdjustmentType
    {
        return match ($this) {
            self::RushService,
            self::Delivery,
            self::LatePayment,
            self::Cancellation => AdjustmentType::Fee,
            default => AdjustmentType::Discount,
        };
    }
}
