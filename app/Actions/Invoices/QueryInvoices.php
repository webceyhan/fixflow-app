<?php

namespace App\Actions\Invoices;

use App\Models\Invoice;
use App\Support\QueryBuilder;
use App\Support\QueryBuilder\AllowedFilter;

class QueryInvoices
{
    public function handle(): QueryBuilder
    {
        return QueryBuilder::for(Invoice::class)
            ->allowedFilters([
                AllowedFilter::search(['ticket.customer.name']),
                AllowedFilter::exact('status'),
                AllowedFilter::date(),
            ])
            ->allowedSorts(['id', 'total', 'due_date', 'created_at']);
    }
}
