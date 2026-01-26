<?php

namespace App\Enums;

use App\Enums\Concerns\Collectible;
use App\Enums\Concerns\HasNextCase;

enum Priority: string
{
    use Collectible, HasNextCase;

    /**
     * Low priority for items that can be addressed within a longer timeframe.
     */
    case Low = 'low';

    /**
     * Medium priority for standard items requiring routine attention.
     *
     * @default
     */
    case Medium = 'medium';

    /**
     * High priority for important items requiring prompt attention.
     */
    case High = 'high';
}
