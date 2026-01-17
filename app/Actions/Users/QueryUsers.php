<?php

namespace App\Actions\Users;

use App\Actions\Concerns\HasQueryHelpers;
use App\Models\User;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class QueryUsers
{
    use HasQueryHelpers;

    public function handle(): QueryBuilder
    {
        return QueryBuilder::for(User::class)
            ->allowedFilters([
                $this->searchFilter(['name', 'email', 'phone']),
                AllowedFilter::exact('role'),
                AllowedFilter::exact('status'),
                $this->relativeDateFilter('created_at'),
            ])
            ->allowedSorts(['name', 'email', 'phone', 'created_at'])
            ->defaultSort('-created_at');
    }
}
