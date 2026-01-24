<?php

namespace App\Enums;

use App\Enums\Concerns\Collectible;
use App\Enums\Concerns\HasNextCase;

enum CustomerType: string
{
    use Collectible, HasNextCase;

    /**
     * Represents an individual customer without a company.
     *
     * @default
     */
    case Individual = 'individual';

    /**
     * Represents a business customer with a company.
     */
    case Business = 'business';
}
