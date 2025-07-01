<?php

namespace App\Enums;

use App\Enums\Concerns\HasValues;

enum TaskType: string
{
    use HasValues;

    /**
     * Represents a task involving the repair of a device.
     * This type of task addresses issues or damages in a device that require fixing.
     *
     * @default
     */
    case Repair = 'repair';

    /**
     * Represents diagnostic work, problem identification, and initial assessment.
     * This includes troubleshooting, analysis, inspection, cost estimation, and determining root causes.
     */
    case Diagnostic = 'diagnostic';

    /**
     * Represents testing or quality assurance work.
     * This includes functional testing and verification of repairs.
     */
    case Testing = 'testing';

    /**
     * Represents cleaning services for devices.
     * This includes physical cleaning and removal of dust, debris, or contamination.
     */
    case Cleaning = 'cleaning';

    /**
     * Represents data backup services before repair work.
     * This includes creating safety copies of customer data and system backups.
     */
    case Backup = 'backup';

    /**
     * Represents data recovery services.
     * This includes retrieving lost or corrupted data from damaged devices.
     */
    case Recovery = 'recovery';

    /**
     * Represents a task involving the upgrade of a device.
     * Upgrade tasks involve improving the functionality or performance of a device by replacing components or software.
     */
    case Upgrade = 'upgrade';

    /**
     * Represents tuning and optimization services.
     * This includes adjusting device settings, performance optimization, and fine-tuning for optimal operation.
     */
    case Tuning = 'tuning';

    /**
     * Represents a task involving the installation of a device.
     * Installation tasks entail setting up and configuring a device for its intended use.
     */
    case Installation = 'installation';

    /**
     * Represents any other task not listed.
     */
    case Other = 'other';
}
