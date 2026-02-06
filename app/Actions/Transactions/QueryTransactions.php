<?php

namespace App\Actions\Transactions;

use App\Models\Transaction;
use App\Support\QueryBuilder;
use App\Support\QueryBuilder\AllowedFilter;

class QueryTransactions
{
    public function handle(): QueryBuilder
    {
        return QueryBuilder::for(Transaction::class)
            ->allowedFilters([
                AllowedFilter::search(['note']),
                AllowedFilter::exact('method'),
                AllowedFilter::exact('type'),
                AllowedFilter::date(),
            ])
            ->allowedSorts(['method', 'amount', 'created_at']);
    }
}
