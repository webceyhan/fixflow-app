<?php

namespace App\Enums;

use App\Enums\Attributes\Type;
use App\Enums\Concerns\HasAttributes;
use App\Enums\Concerns\HasNext;
use App\Enums\Concerns\HasValues;

enum AdjustmentReason: string
{
    use HasAttributes, HasNext, HasValues;

    // DISCOUNT REASONS ////////////////////////////////////////////////////////////////////////////

    /**
     * Bulk service discount for multiple services or devices.
     * Applied when customer has multiple repairs or services.
     */
    #[Type(AdjustmentType::Discount, 8.0)]
    case BulkService = 'bulk_service_discount';

    /**
     * Promotional discount for marketing campaigns.
     * Applied during special offers, sales events, and marketing initiatives.
     */
    #[Type(AdjustmentType::Discount, 15.0)]
    case Promotion = 'promotional_discount';

    /**
     * Price match discount to match competitor pricing.
     * Applied when customer provides competitor quote.
     */
    #[Type(AdjustmentType::Discount)]
    case PriceMatch = 'price_match_discount';

    // FEE REASONS /////////////////////////////////////////////////////////////////////////////////

    /**
     * Rush service fee for expedited completion.
     * Applied when customer needs same-day or urgent service.
     */
    #[Type(AdjustmentType::Fee, 25.0)]
    case RushService = 'rush_service_fee';

    /**
     * Service fee for operational overhead and logistics.
     * Applied for processing, travel, handling, and administrative costs.
     */
    #[Type(AdjustmentType::Fee, 3.0)]
    case Service = 'service_fee';

    /**
     * Late payment fee for overdue invoices.
     * Applied when customer pays after the due date.
     */
    #[Type(AdjustmentType::Fee, 1.5)]
    case LatePayment = 'late_payment_fee';

    // COMPENSATION REASONS ////////////////////////////////////////////////////////////////////////

    /**
     * Service delay compensation for late completion.
     * Applied when service takes longer than promised timeline.
     */
    #[Type(AdjustmentType::Compensation, 10.0)]
    case ServiceDelay = 'service_delay_compensation';

    /**
     * Damage incident compensation for device damage during service.
     * Applied when device is damaged while in service custody.
     */
    #[Type(AdjustmentType::Compensation)]
    case DamageIncident = 'damage_incident_compensation';

    /**
     * Rework compensation for service requiring redoing.
     * Applied when initial repair fails and requires additional work.
     */
    #[Type(AdjustmentType::Compensation, 15.0)]
    case Rework = 'rework_compensation';

    // BONUS REASONS ///////////////////////////////////////////////////////////////////////////////

    /**
     * New customer welcome bonus for first-time customers.
     * Applied to encourage customer acquisition and provide initial value.
     *
     * @default
     */
    #[Type(AdjustmentType::Bonus, 10.0)]
    case Welcome = 'welcome_bonus';

    /**
     * Loyalty bonus for repeat customers.
     * Applied based on customer history and relationship.
     */
    #[Type(AdjustmentType::Bonus, 5.0)]
    case Loyalty = 'loyalty_bonus';

    /**
     * Referral bonus for bringing new customers.
     * Applied when customer successfully refers new business.
     */
    #[Type(AdjustmentType::Bonus, 20.0)]
    case Referral = 'referral_bonus';

    // METHODS /////////////////////////////////////////////////////////////////////////////////////

    /**
     * Get the associated adjustment type for this reason.
     */
    public function type(): AdjustmentType
    {
        return $this->attribute(Type::class)->getArguments()[0];
    }

    /**
     * Get the typical percentage for this adjustment reason.
     * Returns null for fixed-amount adjustments.
     */
    public function percentage(): ?float
    {
        return $this->attribute(Type::class)->getArguments()[1] ?? null;
    }
}
