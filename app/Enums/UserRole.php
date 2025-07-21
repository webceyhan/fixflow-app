<?php

namespace App\Enums;

use App\Enums\Concerns\HasNext;
use App\Enums\Concerns\HasValues;

enum UserRole: string
{
    use HasNext, HasValues;

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
