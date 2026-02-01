<?php

namespace App\Enums;

use App\Enums\Attributes\Tag;
use App\Enums\Concerns\Collectible;
use App\Enums\Concerns\HasAttributes;
use App\Enums\Concerns\HasNextCase;

enum AdjustmentReason: string
{
    use Collectible,
        HasAttributes,
        HasNextCase;

    // DISCOUNT REASONS ////////////////////////////////////////////////////////////////////////////

    /**
     * Bulk service discount for multiple services.
     */
    #[Tag(AdjustmentType::Discount)]
    case BulkService = 'bulk_service';

    /**
     * Promotional discount for marketing campaigns.
     */
    #[Tag(AdjustmentType::Discount)]
    case Promotion = 'promotion';

    /**
     * Loyalty discount for repeat customers.
     */
    #[Tag(AdjustmentType::Discount)]
    case Loyalty = 'loyalty';

    /**
     * Welcome bonus for new customers.
     *
     * @default
     */
    #[Tag(AdjustmentType::Discount)]
    case Welcome = 'welcome';

    /**
     * Compensation for service issues or delays.
     */
    #[Tag(AdjustmentType::Discount)]
    case Compensation = 'compensation';

    // FEE REASONS /////////////////////////////////////////////////////////////////////////////////

    /**
     * Rush service fee for expedited completion.
     */
    #[Tag(AdjustmentType::Fee)]
    case RushService = 'rush_service';

    /**
     * Delivery fee for parts or device transportation.
     */
    #[Tag(AdjustmentType::Fee)]
    case Delivery = 'delivery';

    /**
     * Late payment fee for overdue invoices.
     */
    #[Tag(AdjustmentType::Fee)]
    case LatePayment = 'late_payment';

    /**
     * Cancellation fee for cancelled services.
     */
    #[Tag(AdjustmentType::Fee)]
    case Cancellation = 'cancellation';

    // METHODS /////////////////////////////////////////////////////////////////////////////////////

    /**
     * Get the associated adjustment type for this reason.
     */
    public function type(): AdjustmentType
    {
        return $this->attribute(Tag::class)->value;
    }
}
