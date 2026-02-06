<?php

namespace App\Observers;

use App\Actions\Users\RefreshAssignableUsers;
use App\Models\User;

class UserObserver
{
    public function __construct(private RefreshAssignableUsers $refreshAssignableUsers) {}

    /**
     * Handle the User "created" event.
     */
    public function created(User $user): void
    {
        $this->refreshAssignableUsers->handle();
    }

    /**
     * Handle the User "updated" event.
     */
    public function updated(User $user): void
    {
        $this->refreshAssignableUsers->handle();
    }

    /**
     * Handle the User "deleted" event.
     */
    public function deleted(User $user): void
    {
        $this->refreshAssignableUsers->handle();
    }

    /**
     * Handle the User "restored" event.
     */
    public function restored(User $user): void
    {
        $this->refreshAssignableUsers->handle();
    }

    /**
     * Handle the User "force deleted" event.
     */
    public function forceDeleted(User $user): void
    {
        $this->refreshAssignableUsers->handle();
    }
}
