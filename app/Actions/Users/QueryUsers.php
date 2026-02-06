<?php

namespace App\Actions\Users;

use App\Models\User;
use App\Support\QueryBuilder;
use App\Support\QueryBuilder\AllowedFilter;

class QueryUsers
{
    public function handle(): QueryBuilder
    {
        return QueryBuilder::for(User::class)
            ->allowedFilters([
                AllowedFilter::search(['name', 'email', 'phone']),
                AllowedFilter::exact('role'),
                AllowedFilter::exact('status'),
                AllowedFilter::date(),
            ])
            ->allowedSorts(['name', 'email', 'created_at']);
    }
}
