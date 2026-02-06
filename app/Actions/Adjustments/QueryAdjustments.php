<?php

namespace App\Actions\Adjustments;

use App\Models\Adjustment;
use App\Support\QueryBuilder;
use App\Support\QueryBuilder\AllowedFilter;

class QueryAdjustments
{
    public function handle(): QueryBuilder
    {
        return QueryBuilder::for(Adjustment::class)
            ->allowedFilters([
                AllowedFilter::search(['note']),
                AllowedFilter::exact('type'),
                AllowedFilter::exact('reason'),
                AllowedFilter::date(),
            ])
            ->allowedSorts(['id', 'amount', 'created_at']);
    }
}
