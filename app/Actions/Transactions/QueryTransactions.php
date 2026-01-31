<?php

namespace App\Actions\Transactions;

use App\Actions\Concerns\HasQueryHelpers;
use App\Models\Transaction;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class QueryTransactions
{
    use HasQueryHelpers;

    public function handle(): QueryBuilder
    {
        return QueryBuilder::for(Transaction::class)
            ->allowedFilters([
                $this->searchFilter(['note']),
                AllowedFilter::exact('method'),
                AllowedFilter::exact('type'),
                $this->relativeDateFilter('created_at'),
            ])
            ->allowedSorts(['id', 'amount', 'created_at'])
            ->defaultSort('-created_at');
    }
}
