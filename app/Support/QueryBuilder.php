<?php

namespace App\Support;

use App\Support\QueryBuilder\AllowedFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Spatie\QueryBuilder\AllowedSort;
use Spatie\QueryBuilder\QueryBuilder as SpatieQueryBuilder;

/**
 * @template TModel of Model
 *
 * @extends SpatieQueryBuilder<TModel>
 *
 * @method self<TModel> with(array<string>|string ...$relations)
 * @method self<TModel> allowedSorts(AllowedSort|string ...$sorts)
 * @method self<TModel> allowedFilters(AllowedFilter|string ...$filters)
 *
 * @mixin SpatieQueryBuilder<TModel>|TModel
 */
class QueryBuilder extends SpatieQueryBuilder
{
    private const DEFAULT_PAGE_SIZE = 10;

    private const MAX_PAGE_SIZE = 100;

    private const DEFAULT_SORT = '-created_at';

    /**
     * @param Builder<TModel>|Relation<TModel, *, *>  $subject
     */
    public function __construct(Builder|Relation $subject, ?Request $request = null)
    {
        parent::__construct($subject, $request);

        $this->defaultSort(self::DEFAULT_SORT);
    }

    /**
     * Paginate the results with dynamic per_page from request.
     *
     * @param  int|null  $perPage
     * @param  array<int, string>  $columns
     * @param  string  $pageName
     * @param  int|null  $page
     * @return LengthAwarePaginator<int, TModel>
     *
     * @throws \InvalidArgumentException
     */
    public function paginate($perPage = null, $columns = ['*'], $pageName = 'page', $page = null): LengthAwarePaginator
    {
        $perPage = (int) ($perPage ?? request()->query('per_page', self::DEFAULT_PAGE_SIZE));

        if ($perPage < 1) {
            $perPage = self::DEFAULT_PAGE_SIZE;
        }

        $perPage = min($perPage, self::MAX_PAGE_SIZE);

        return parent::paginate($perPage, $columns, $pageName, $page);
    }
}
