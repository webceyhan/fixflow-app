<?php

namespace App\Enums;

use App\Enums\Concerns\HasValues;

enum AdjustmentReason: string
{
    use HasValues;

    // DISCOUNT REASONS ////////////////////////////////////////////////////////////////////////////

    /**
     * Bulk service discount for multiple services or devices.
     * Applied when customer has multiple repairs or services.
     */
    case BulkService = 'bulk_service_discount';

    /**
     * Promotional discount for marketing campaigns.
     * Applied during special offers, sales events, and marketing initiatives.
     */
    case Promotion = 'promotional_discount';

    /**
     * Price match discount to match competitor pricing.
     * Applied when customer provides competitor quote.
     */
    case PriceMatch = 'price_match_discount';

    // FEE REASONS /////////////////////////////////////////////////////////////////////////////////

    /**
     * Rush service fee for expedited completion.
     * Applied when customer needs same-day or urgent service.
     */
    case RushService = 'rush_service_fee';

    /**
     * Service fee for operational overhead and logistics.
     * Applied for processing, travel, handling, and administrative costs.
     */
    case Service = 'service_fee';

    /**
     * Late payment fee for overdue invoices.
     * Applied when customer pays after the due date.
     */
    case LatePayment = 'late_payment_fee';

    // COMPENSATION REASONS ////////////////////////////////////////////////////////////////////////

    /**
     * Service delay compensation for late completion.
     * Applied when service takes longer than promised timeline.
     */
    case ServiceDelay = 'service_delay_compensation';

    /**
     * Damage incident compensation for device damage during service.
     * Applied when device is damaged while in service custody.
     */
    case DamageIncident = 'damage_incident_compensation';

    /**
     * Rework compensation for service requiring redoing.
     * Applied when initial repair fails and requires additional work.
     */
    case Rework = 'rework_compensation';

    // BONUS REASONS ///////////////////////////////////////////////////////////////////////////////

    /**
     * New customer welcome bonus for first-time customers.
     * Applied to encourage customer acquisition and provide initial value.
     *
     * @default
     */
    case Welcome = 'welcome_bonus';

    /**
     * Loyalty bonus for repeat customers.
     * Applied based on customer history and relationship.
     */
    case Loyalty = 'loyalty_bonus';

    /**
     * Referral bonus for bringing new customers.
     * Applied when customer successfully refers new business.
     */
    case Referral = 'referral_bonus';

    // METHODS /////////////////////////////////////////////////////////////////////////////////////

    /**
     * Get the associated adjustment type for this reason.
     */
    public function type(): AdjustmentType
    {
        return match ($this) {
            // Discount reasons
            self::BulkService,
            self::Promotion,
            self::PriceMatch => AdjustmentType::Discount,
            // Fee reasons
            self::RushService,
            self::Service,
            self::LatePayment => AdjustmentType::Fee,
            // Compensation reasons
            self::ServiceDelay,
            self::DamageIncident,
            self::Rework => AdjustmentType::Compensation,
            // Bonus reasons
            self::Welcome,
            self::Loyalty,
            self::Referral => AdjustmentType::Bonus,
        };
    }

    /**
     * Get the typical percentage for this adjustment reason.
     * Returns null for fixed-amount adjustments.
     */
    public function percentage(): ?float
    {
        return match ($this) {
            // Discount percentages
            self::BulkService => 8.0,      // 8% for multiple services
            self::Promotion => 15.0,       // 15% for promotional campaigns
            self::PriceMatch => null,      // Variable based on competitor

            // Fee percentages
            self::RushService => 25.0,     // 25% surcharge for rush service
            self::Service => 3.0,          // 3% for processing/handling
            self::LatePayment => 1.5,      // 1.5% monthly late fee

            // Compensation percentages
            self::ServiceDelay => 10.0,    // 10% compensation for delays
            self::DamageIncident => null,  // Variable based on damage
            self::Rework => 15.0,          // 15% compensation for rework

            // Bonus percentages
            self::Welcome => 10.0,         // 10% welcome bonus
            self::Loyalty => 5.0,          // 5% loyalty bonus
            self::Referral => 20.0,        // 20% referral bonus

            default => null,
        };
    }
}
