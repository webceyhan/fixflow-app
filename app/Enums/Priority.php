<?php

namespace App\Enums;

use App\Enums\Concerns\HasValues;

enum Priority: string
{
    use HasValues;

    /**
     * The ticket has a low priority and can be addressed within 2 weeks.
     * Suitable for non-urgent customer requests, awaiting parts, or when workload is heavy.
     */
    case Low = 'low';

    /**
     * The ticket has a medium priority and should be addressed within 1 week.
     * Standard business priority for most customer requests and routine workflow.
     *
     * @default
     */
    case Medium = 'medium';

    /**
     * The ticket has a high priority and should be addressed within 2 days.
     * Important for premium customers, express service requests, or business commitments.
     */
    case High = 'high';

    /**
     * The ticket has an urgent priority and requires immediate attention (same day).
     * Critical for VIP customers, emergency requests, or when delays impact business operations.
     */
    case Urgent = 'urgent';
}
