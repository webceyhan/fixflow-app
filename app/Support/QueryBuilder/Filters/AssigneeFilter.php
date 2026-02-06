<?php

namespace App\Support\QueryBuilder\Filters;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Spatie\QueryBuilder\Filters\Filter;

class AssigneeFilter implements Filter
{
    private const PRESET_ME = 'me';

    private const PRESET_OTHERS = 'others';

    private const PRESET_NONE = 'none';

    public function __construct(
        private string $column = 'assignee_id',
    ) {}

    /**
     * Create an assignee filter for query builder.
     *
     * Supports filtering by:
     * - 'me': Assigned to the current user
     * - 'others': Assigned to any user except the current user
     * - 'none': Not assigned to any user
     * - numeric ID: Assigned to a specific user ID
     */
    public function __invoke(Builder $query, mixed $value, string $property): void
    {
        match ($value) {
            self::PRESET_ME => $query->where($this->column, Auth::id()),

            self::PRESET_OTHERS => $query->where($this->column, '!=', Auth::id())->whereNotNull($this->column),

            self::PRESET_NONE => $query->whereNull($this->column),

            default => is_numeric($value) ? $query->where($this->column, $value) : null,
        };
    }
}
