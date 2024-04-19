<?php

namespace App\Enums;

use App\Enums\Concerns\HasValues;

enum TaskType: string
{
    use HasValues;

    /**
     * Represents a task involving the repair of a device.
     * This type of task addresses issues or damages in a device that require fixing.
     * @default
     */
    case Repair = 'repair';

    /**
     * Represents a task involving the maintenance of a device.
     * Maintenance tasks focus on keeping a device in optimal condition, preventing failures or breakdowns.
     */
    case Maintenance = 'maintenance';

    /**
     * Represents a task involving the installation of a device.
     * Installation tasks entail setting up and configuring a device for its intended use.
     */
    case Installation = 'installation';

    /**
     * Represents a task involving the inspection of a device.
     * Inspection tasks involve assessing the condition or performance of a device to identify any issues or potential problems.
     */
    case Inspection = 'inspection';

    /**
     * Represents a task involving the upgrade of a device.
     * Upgrade tasks involve improving the functionality or performance of a device by replacing components or software.
     */
    case Upgrade = 'upgrade';

    /**
     * Represents any other task not listed, such as consultation or training.
     */
    case Other = 'other';
}
