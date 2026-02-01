<?php

namespace App\Enums;

use App\Enums\Concerns\Collectible;
use App\Enums\Concerns\HasNextCase;

enum TransactionMethod: string
{
    use Collectible,
        HasNextCase;

    /**
     * Represents transactions via cash.
     *
     * @default
     */
    case Cash = 'cash';

    /**
     * Represents transactions via credit or debit cards.
     */
    case Card = 'card';

    /**
     * Represents transaction via online payment systems.
     */
    case Online = 'online';
}
