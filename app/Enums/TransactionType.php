<?php

namespace App\Enums;

use App\Enums\Concerns\HasValues;

enum TransactionType: string
{
    use HasValues;

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
