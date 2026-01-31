<?php

namespace App\Actions\Invoices;

use App\Actions\Concerns\HasQueryHelpers;
use App\Models\Invoice;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class QueryInvoices
{
    use HasQueryHelpers;

    public function handle(): QueryBuilder
    {
        return QueryBuilder::for(Invoice::class)
            ->allowedFilters([
                AllowedFilter::callback('search', function ($query, string $value) {
                    $query->whereHas('ticket.customer', function ($q) use ($value) {
                        $q->where('name', 'like', "%{$value}%");
                    });
                }),
                AllowedFilter::exact('status'),
                $this->relativeDateFilter('created_at'),
            ])
            ->allowedSorts(['id', 'total', 'due_date', 'created_at'])
            ->defaultSort('-created_at');
    }
}
