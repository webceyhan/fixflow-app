<?php

namespace App\Support\QueryBuilder;

use App\Support\QueryBuilder\Filters\AssigneeFilter;
use App\Support\QueryBuilder\Filters\DateFilter;
use App\Support\QueryBuilder\Filters\SearchFilter;
use Spatie\QueryBuilder\AllowedFilter as SpatieAllowedFilter;

class AllowedFilter extends SpatieAllowedFilter
{
    public static function search(array $searchables): AllowedFilter
    {
        return self::custom('search', new SearchFilter($searchables));
    }

    public static function date(string $column = 'created_at'): AllowedFilter
    {
        return self::custom($column, new DateFilter($column));
    }

    public static function assignee(): AllowedFilter
    {
        return self::custom('assignee', new AssigneeFilter);
    }
}
