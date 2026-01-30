<?php

namespace App\Actions\Tasks;

use App\Actions\Concerns\HasQueryHelpers;
use App\Models\Task;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class QueryTasks
{
    use HasQueryHelpers;

    public function handle(): QueryBuilder
    {
        return QueryBuilder::for(Task::class)
            ->allowedFilters([
                $this->searchFilter(['note']),
                $this->assigneeFilter(),
                AllowedFilter::exact('type'),
                AllowedFilter::exact('priority'),
                AllowedFilter::exact('status'),
                $this->relativeDateFilter('created_at'),
            ])
            ->allowedSorts(['type', 'cost', 'approved_at', 'created_at'])
            ->defaultSort('-created_at');
    }
}
