<?php

namespace App\Actions\Customers;

use App\Actions\Concerns\HasQueryHelpers;
use App\Models\Customer;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class QueryCustomers
{
    use HasQueryHelpers;

    public function handle(): QueryBuilder
    {
        return QueryBuilder::for(Customer::class)
            ->allowedFilters([
                $this->searchFilter(['name', 'company', 'email', 'phone']),
                AllowedFilter::exact('type'),
                AllowedFilter::exact('status'),
                $this->relativeDateFilter('created_at'),
            ])
            ->allowedSorts(['name', 'email', 'phone', 'created_at'])
            ->defaultSort('-created_at');
    }
}
