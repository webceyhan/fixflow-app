<?php

namespace App\Support\QueryBuilder\Filters;

use App\Enums\DatePreset;
use Illuminate\Database\Eloquent\Builder;
use Spatie\QueryBuilder\Exceptions\InvalidFilterValue;
use Spatie\QueryBuilder\Filters\Filter;

class DateFilter implements Filter
{
    public function __construct(
        private string $column = 'created_at'
    ) {}

    /**
     * Create a date filter for query builder.
     *
     * Supports both DatePreset enum values and date ranges with 'from' and 'to' keys.
     */
    public function __invoke(Builder $query, mixed $value, string $property): void
    {
        // First, try to parse as a preset (string value)
        if (is_string($value) && $preset = DatePreset::tryFrom($value)) {
            [$from, $to] = $preset->range();
            $this->queryBetweenDates($query, $this->column, $from, $to);

            return;
        }

        // Otherwise, try to parse as a range (indexed array with from/to values)
        if (is_array($value) && ($value[0] ?? $value[1] ?? null)) {
            [$from, $to] = $value + [null, null]; // Ensure we have both indices
            $this->queryBetweenDates($query, $this->column, $from, $to);

            return;
        }

        // If neither preset nor valid range, throw exception
        throw InvalidFilterValue::make($value);
    }

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
}
