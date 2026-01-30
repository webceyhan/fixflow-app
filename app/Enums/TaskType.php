<?php

namespace App\Enums;

use App\Enums\Concerns\Collectible;
use App\Enums\Concerns\HasNextCase;

enum TaskType: string
{
    use Collectible, HasNextCase;

    /**
     * Repair work on the device.
     *
     * @default
     */
    case Repair = 'repair';

    /**
     * Diagnostic work to identify issues.
     */
    case Diagnosis = 'diagnosis';

    /**
     * Testing the device or components.
     */
    case Testing = 'testing';

    /**
     * Calibration of device components.
     */
    case Calibration = 'calibration';

    /**
     * Installation of software or components.
     */
    case Installation = 'installation';

    /**
     * Upgrade of software or hardware.
     */
    case Upgrade = 'upgrade';

    /**
     * Maintenance and cleaning work.
     */
    case Maintenance = 'maintenance';

    /**
     * Cleaning of device components.
     */
    case Cleaning = 'cleaning';

    /**
     * Other types of work not categorized above.
     */
    case Other = 'other';
}
