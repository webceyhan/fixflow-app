<?php

namespace App\Actions\Orders;

use App\Actions\Concerns\HasQueryHelpers;
use App\Models\Order;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class QueryOrders
{
    use HasQueryHelpers;

    public function handle(): QueryBuilder
    {
        return QueryBuilder::for(Order::class)
            ->allowedFilters([
                $this->searchFilter(['name', 'supplier', 'note']),
                $this->assigneeFilter(),
                AllowedFilter::exact('status'),
                $this->relativeDateFilter('created_at'),
                $this->relativeDateFilter('due_date'),
            ])
            ->allowedSorts(['name', 'cost', 'due_date', 'approved_at', 'created_at'])
            ->defaultSort('-created_at');
    }
}
