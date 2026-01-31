<?php

namespace App\Enums;

use App\Enums\Concerns\Collectible;
use App\Enums\Concerns\HasNextCase;

enum TransactionType: string
{
    use Collectible, HasNextCase;

    /**
     * Represents a deposit/advance payment.
     */
    case Deposit = 'deposit';

    /**
     * Represents a payment transaction.
     *
     * @default
     */
    case Payment = 'payment';

    /**
     * Represents a refund transaction.
     */
    case Refund = 'refund';
}
