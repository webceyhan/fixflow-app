<?php

namespace App\Actions\Users;

use App\Models\User;

class UpdateUser
{
    public function handle(User $user, array $data): User
    {
        $user->update([
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'],
            'note' => $data['note'] ?? null,
            'role' => $data['role'],
            'status' => $data['status'],
        ]);

        return $user;
    }
}
