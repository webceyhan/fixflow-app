<?php

namespace App\Enums;

use App\Enums\Concerns\HasValues;

enum Priority: string
{
    use HasValues;

    /**
     * The model has a low priority and can be addressed later.
     */
    case Low = 'low';

    /**
     * The model has a normal priority and should be addressed soon.
     * @default
     */
    case Normal = 'normal';

    /**
     * The model has a high priority and should be addressed as soon as possible.
     */
    case High = 'high';
}
