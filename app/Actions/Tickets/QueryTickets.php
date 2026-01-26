<?php

namespace App\Actions\Tickets;

use App\Actions\Concerns\HasQueryHelpers;
use App\Models\Ticket;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class QueryTickets
{
    use HasQueryHelpers;

    public function handle(): QueryBuilder
    {
        return QueryBuilder::for(Ticket::class)
            ->allowedFilters([
                $this->searchFilter(['title']),
                $this->assigneeFilter(),
                AllowedFilter::exact('priority'),
                AllowedFilter::exact('status'),
                $this->relativeDateFilter('created_at'),
            ])
            ->allowedSorts(['title', 'due_date', 'created_at'])
            ->defaultSort('-created_at');
    }
}
