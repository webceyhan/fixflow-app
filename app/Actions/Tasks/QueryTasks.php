<?php

namespace App\Actions\Tasks;

use App\Models\Task;
use App\Support\QueryBuilder;
use App\Support\QueryBuilder\AllowedFilter;

class QueryTasks
{
    public function handle(): QueryBuilder
    {
        return QueryBuilder::for(Task::class)
            ->allowedFilters([
                AllowedFilter::search(['note']),
                AllowedFilter::assignee(),
                AllowedFilter::exact('type'),
                AllowedFilter::exact('priority'),
                AllowedFilter::exact('status'),
                AllowedFilter::date(),
            ])
            ->allowedSorts(['type', 'cost', 'approved_at', 'created_at']);
    }
}
