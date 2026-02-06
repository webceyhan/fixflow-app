<?php

namespace App\Actions\Customers;

use App\Models\Customer;
use App\Support\QueryBuilder;
use App\Support\QueryBuilder\AllowedFilter;

class QueryCustomers
{
    public function handle(): QueryBuilder
    {
        return QueryBuilder::for(Customer::class)
            ->allowedFilters([
                AllowedFilter::search(['name', 'company', 'email', 'phone']),
                AllowedFilter::exact('type'),
                AllowedFilter::exact('status'),
                AllowedFilter::date(),
            ])
            ->allowedSorts(['name', 'email', 'phone', 'created_at']);
    }
}
