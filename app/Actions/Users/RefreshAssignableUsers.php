<?php

namespace App\Actions\Users;

use Illuminate\Support\Facades\Cache;

class RefreshAssignableUsers
{
    /**
     * Clear the cached assignable users list.
     */
    public function handle(): void
    {
        Cache::forget(GetAssignableUsers::class);
    }
}
