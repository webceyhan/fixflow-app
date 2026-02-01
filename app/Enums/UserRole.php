<?php

namespace App\Enums;

use App\Enums\Concerns\Collectible;
use App\Enums\Concerns\HasNextCase;

enum UserRole: string
{
    use Collectible,
        HasNextCase;

    /**
     * Represents the administrator with full access to the application.
     */
    case Admin = 'admin';

    /**
     * Represents a manager responsible for overseeing operations and managing employees.
     */
    case Manager = 'manager';

    /**
     * Represents a technician responsible for handling technical tasks and repairs.
     *
     * @default
     */
    case Technician = 'technician';
}
