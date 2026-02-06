<?php

namespace App\Actions\Orders;

use App\Models\Order;
use App\Support\QueryBuilder;
use App\Support\QueryBuilder\AllowedFilter;

class QueryOrders
{
    public function handle(): QueryBuilder
    {
        return QueryBuilder::for(Order::class)
            ->allowedFilters([
                AllowedFilter::search(['name', 'supplier', 'note']),
                AllowedFilter::assignee(),
                AllowedFilter::exact('status'),
                AllowedFilter::date(),
            ])
            ->allowedSorts(['name', 'cost', 'due_date', 'approved_at', 'created_at']);
    }
}
