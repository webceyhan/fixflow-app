<?php

namespace App\Actions\Adjustments;

use App\Actions\Concerns\HasQueryHelpers;
use App\Models\Adjustment;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class QueryAdjustments
{
    use HasQueryHelpers;

    public function handle(): QueryBuilder
    {
        return QueryBuilder::for(Adjustment::class)
            ->allowedFilters([
                $this->searchFilter(['note']),
                AllowedFilter::exact('type'),
                AllowedFilter::exact('reason'),
                $this->relativeDateFilter('created_at'),
            ])
            ->allowedSorts(['id', 'amount', 'created_at'])
            ->defaultSort('-created_at');
    }
}
