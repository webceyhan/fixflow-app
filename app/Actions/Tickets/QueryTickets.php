<?php

namespace App\Actions\Tickets;

use App\Models\Ticket;
use App\Support\QueryBuilder;
use App\Support\QueryBuilder\AllowedFilter;

class QueryTickets
{
    /**
     * @return QueryBuilder<Ticket>
     */
    public function handle(): QueryBuilder
    {
        return QueryBuilder::for(Ticket::class)
            ->allowedFilters(
                AllowedFilter::search(['title']),
                AllowedFilter::assignee(),
                AllowedFilter::exact('priority'),
                AllowedFilter::exact('status'),
                AllowedFilter::date(),
            )
            ->allowedSorts('title', 'due_date', 'created_at');
    }
}
