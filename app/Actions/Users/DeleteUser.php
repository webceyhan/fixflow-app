<?php

namespace App\Actions\Users;

use App\Models\User;

class DeleteUser
{
    public function handle(User $user): bool
    {
        return $user->delete();
    }
}
