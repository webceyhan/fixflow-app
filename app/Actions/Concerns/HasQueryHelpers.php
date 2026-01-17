<?php

namespace App\Actions\Concerns;

use Illuminate\Database\Eloquent\Builder;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\Exceptions\InvalidFilterValue;

trait HasQueryHelpers
{
    // Relative date presets

    private const PRESET_TODAY = 'today';

    private const PRESET_LAST_WEEK = 'last_week';

    private const PRESET_LAST_MONTH = 'last_month';

    private const PRESET_LAST_YEAR = 'last_year';

    // Allowed preset groups

    private const ALLOWED_RELATIVE_DATE_PRESETS = [
        self::PRESET_TODAY,
        self::PRESET_LAST_WEEK,
        self::PRESET_LAST_MONTH,
        self::PRESET_LAST_YEAR,
    ];

    /**
     * Create a search filter for multiple columns.
     *
     * @param  array<int, string>  $columns
     */
    protected function searchFilter(array $columns): AllowedFilter
    {
        return AllowedFilter::callback('search', function ($query, $value) use ($columns): void {
            $query->whereAny($columns, 'like', "%{$value}%");
        });
    }

    /**
     * Create a relative date filter that supports preset values:
     * - 'today', 'last_week', 'last_month', etc.
     */
    protected function relativeDateFilter(string $column = 'created_at'): AllowedFilter
    {
        return AllowedFilter::callback($column, function ($query, $value) use ($column): void {
            // Validate preset value
            if (! in_array($value, self::ALLOWED_RELATIVE_DATE_PRESETS)) {
                throw InvalidFilterValue::make($value);
            }

            [$from, $to] = $this->convertPresetToDateRange($value);

            $this->queryBetweenDates($query, $column, $from, $to);
        });
    }

    /**
     * Create a date range filter that supports:
     * - From date: "2022-01-01," (>= 2022-01-01)
     * - Until date: ",2022-01-01" (<= 2022-01-01)
     * - Date range: "2022-01-01,2023-01-01" (>= 2022-01-01 AND <= 2023-01-01)
     */
    protected function dateRangeFilter(string $column = 'created_at'): AllowedFilter
    {
        return AllowedFilter::callback($column, function ($query, $value) use ($column): void {
            // Validate input values
            if (! is_array($value) || ! array_filter($value)) {
                throw InvalidFilterValue::make($value);
            }

            [$from, $to] = $value;

            $this->queryBetweenDates($query, $column, $from, $to);
        });
    }

    // HELPERS /////////////////////////////////////////////////////////////////////////////////////

    /**
     * Apply date range query to the given query.
     */
    private function queryBetweenDates(
        Builder $query,
        string $column,
        ?string $from = null,
        ?string $to = null
    ): void {
        $query
            ->when($from, fn ($q) => $q->whereDate($column, '>=', $from))
            ->when($to, fn ($q) => $q->whereDate($column, '<=', $to));
    }

    /**
     * Convert preset value to date range [from, to].
     *
     * @return array{string, string}
     */
    private function convertPresetToDateRange(string $preset): array
    {
        $now = now();
        $startOfDay = $now->copy()->startOfDay();
        $endOfDay = $now->copy()->endOfDay();

        $from = match ($preset) {
            self::PRESET_TODAY => $startOfDay,
            self::PRESET_LAST_WEEK => $startOfDay->subWeek(),
            self::PRESET_LAST_MONTH => $startOfDay->subMonth(),
            self::PRESET_LAST_YEAR => $startOfDay->subYear(),
        };

        return [$from->toDateString(), $endOfDay->toDateString()];
    }
}
