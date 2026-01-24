<?php

namespace App\Enums;

use App\Enums\Concerns\Collectible;
use App\Enums\Concerns\HasActiveCase;
use App\Enums\Concerns\HasNextCase;

enum CustomerStatus: string
{
    use Collectible, HasActiveCase, HasNextCase;

    /**
     * Represents that the customer account is currently active and in good standing.
     *
     * @default
     */
    case Active = 'active';

    /**
     * Represents that the customer account has been temporarily suspended,
     * typically due to unpaid invoices or disputes.
     */
    case Suspended = 'suspended';

    /**
     * Represents that the customer is no longer doing business,
     * such as closed business, moved away, or manually archived.
     */
    case Archived = 'archived';

    // METHODS /////////////////////////////////////////////////////////////////////////////////////

    public static function activeCases(): array
    {
        return [
            self::Active,
        ];
    }
}
