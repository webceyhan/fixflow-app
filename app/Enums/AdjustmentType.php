<?php

namespace App\Enums;

use App\Enums\Concerns\Collectible;
use App\Enums\Concerns\HasNextCase;

enum AdjustmentType: string
{
    use Collectible,
        HasNextCase;

    /**
     * Discount adjustments that reduce invoice total.
     *
     * @default
     */
    case Discount = 'discount';

    /**
     * Fee adjustments that add to invoice total.
     */
    case Fee = 'fee';
}
