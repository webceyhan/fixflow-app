<?php

namespace App\Enums;

enum DatePreset: string
{
    case Today = 'today';

    case Yesterday = 'yesterday';

    case LastWeek = 'last_week';

    case LastMonth = 'last_month';

    case LastYear = 'last_year';

    // METHODS /////////////////////////////////////////////////////////////////////////////////////

    /**
     * Get date range [from, to] for this preset.
     *
     * @return array{string, string}
     */
    public function range(): array
    {
        $now = now();
        $startOfDay = $now->copy()->startOfDay();
        $endOfDay = $now->copy()->endOfDay();

        $from = match ($this) {
            self::Today => $startOfDay,
            self::Yesterday => $startOfDay->subDay(),
            self::LastWeek => $startOfDay->subWeek(),
            self::LastMonth => $startOfDay->subMonth(),
            self::LastYear => $startOfDay->subYear(),
        };

        $to = match ($this) {
            self::Yesterday => $endOfDay->subDay(),
            default => $endOfDay,
        };

        return [$from->toDateString(), $to->toDateString()];
    }
}
