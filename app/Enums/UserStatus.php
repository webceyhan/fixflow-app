<?php

namespace App\Enums;

use App\Enums\Concerns\HasValues;

enum UserStatus: string
{
    use HasValues;

    /**
     * Represents that the user account is currently active and accessible.
     * @default
     */
    case Active = 'active';

    /**
     * Represents that the user is on official leave, such as vacation or sick leave.
     */
    case OnLeave = 'on_leave';

    /**
     * Represents that the user account has been temporarily suspended,
     * restricting access to certain functionalities.
     */
    case Suspended = 'suspended';

    /**
     * Represents that the user's account has been terminated or permanently deactivated,
     * typically due to employment termination or other reasons.
     */
    case Terminated = 'terminated';

    // METHODS /////////////////////////////////////////////////////////////////////////////////////

    /**
     * Check if the user account is currently active.
     */
    public function isActive(): bool
    {
        return $this === self::Active;
    }
}
