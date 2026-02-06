<?php

namespace App\Support;

use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder as SpatieQueryBuilder;

class QueryBuilder extends SpatieQueryBuilder
{
    private const DEFAULT_PAGE_SIZE = 10;

    private const DEFAULT_SORT = '-created_at';

    public function __construct(EloquentBuilder|Relation $subject, ?Request $request = null)
    {
        parent::__construct($subject, $request);

        $this->defaultSort(self::DEFAULT_SORT);
    }

    /**
     * Paginate the results with dynamic per_page from request.
     */
    public function paginate($perPage = null, $columns = ['*'], $pageName = 'page', $page = null)
    {
        $perPage = $perPage ?? request()->query('per_page', self::DEFAULT_PAGE_SIZE);

        return parent::paginate($perPage, $columns, $pageName, $page);
    }
}
