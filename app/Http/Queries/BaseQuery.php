<?php

namespace App\Http\Queries;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Spatie\QueryBuilder\QueryBuilder;

/**
 * Base Query class for all resource queries.
 *
 * Extends Spatie QueryBuilder to provide consistent filtering, sorting,
 * and pagination functionality across all resources.
 */
abstract class BaseQuery extends QueryBuilder
{
    /**
     * Default sort order for queries.
     *
     * This is used when no specific sort order is requested.
     */
    const DEFAULT_SORT = '-created_at';

    /**
     * Default page size for pagination.
     *
     * This can be overridden by the 'size' query parameter in requests.
     */
    const DEFAULT_PAGE_SIZE = 10;

    /**
     * Paginate the results with configurable page size from request.
     *
     * Allows frontend to control page size via 'size' query parameter.
     * Defaults to 10 items per page if not specified.
     *
     * @param  int|null  $perPage  Optional manual override for page size
     * @return LengthAwarePaginator<mixed>
     */
    public function paginate(?int $perPage = null): LengthAwarePaginator
    {
        $size = $perPage ?? request()->input('size', self::DEFAULT_PAGE_SIZE);

        return parent::paginate($size);
    }
}
