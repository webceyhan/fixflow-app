<?php

namespace App\Enums;

use App\Enums\Concerns\HasNext;
use App\Enums\Concerns\HasValues;

enum AdjustmentType: string
{
    use HasNext, HasValues;

    /**
     * Discount adjustments that reduce invoice total.
     * Used for planned business strategy reductions like bulk service discounts,
     * promotional offers, demographic discounts (senior, student), etc.
     */
    case Discount = 'discount';

    /**
     * Fee adjustments that add to invoice total.
     * Used for additional charges like rush service fees,
     * processing fees, convenience fees, delivery fees, etc.
     */
    case Fee = 'fee';

    /**
     * Compensation adjustments for service failures and issues.
     * Used for service problems like delays, quality issues,
     * billing errors, damage incidents, etc.
     */
    case Compensation = 'compensation';

    /**
     * Bonus adjustments that provide customer rewards.
     * Used for positive customer rewards like loyalty rewards,
     * referral bonuses, review incentives, etc.
     *
     * @default
     */
    case Bonus = 'bonus';

    // METHODS ///////////////////////////////////////////////////////////////////////////////////

    /**
     * Check if this adjustment type is additive (increases invoice total).
     * Only Fee adjustments are considered additive.
     */
    public function isAdditive(): bool
    {
        return $this === self::Fee;
    }
}
