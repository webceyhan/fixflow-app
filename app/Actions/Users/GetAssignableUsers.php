<?php

namespace App\Actions\Users;

use App\Models\User;
use Illuminate\Support\Facades\Cache;

class GetAssignableUsers
{
    /**
     * Get the list of assignable users with caching.
     *
     * Cache is automatically cleared when any user is created, updated, or deleted.
     *
     * @return array<int, array{id: int, name: string, role: string}>
     */
    public function handle(): array
    {
        return Cache::rememberForever(self::class, function () {
            return User::assignable()->get(['id', 'name', 'role'])->toArray();
        });
    }
}
